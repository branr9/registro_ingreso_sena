<?php
/**
 * Modelo UserModel - CRUD completo de usuarios
 * Extiende funcionalidad de User.php (autenticaciÃ³n)
 */

class UserModel
{
    private Database $db;
    private ?PDO $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
    }

    /**
     * Listar usuarios con filtros y paginaciÃ³n
     * 
     * @param array $filters ['search' => '', 'tipo_persona' => '', 'estado' => '', 'rol' => '']
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function paginate(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $where = ['p.deleted_at IS NULL'];
        $params = [];

        // Filtro de bÃºsqueda general
        if (!empty($filters['search'])) {
            $searchValue = '%' . $filters['search'] . '%';
            $where[] = '(p.documento LIKE :search1 OR p.nombres LIKE :search2 OR COALESCE(p.apellidos, "") LIKE :search3 OR COALESCE(p.email, "") LIKE :search4 OR COALESCE(us.username, "") LIKE :search5)';
            $params['search1'] = $searchValue;
            $params['search2'] = $searchValue;
            $params['search3'] = $searchValue;
            $params['search4'] = $searchValue;
            $params['search5'] = $searchValue;
        }

        // Filtro por tipo de persona
        if (!empty($filters['tipo_persona'])) {
            $where[] = 'cpt.codigo = :tipo_persona';
            $params['tipo_persona'] = $filters['tipo_persona'];
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $where[] = 'p.estado = :estado';
            $params['estado'] = $filters['estado'];
        }

        // Filtro por rol (solo si tiene cuenta de sistema)
        if (!empty($filters['rol'])) {
            $where[] = 'crs.codigo = :rol';
            $params['rol'] = $filters['rol'];
        }

        $whereClause = implode(' AND ', $where);

        // Contar total
        $sqlCount = "SELECT COUNT(*) as total 
                     FROM personas p
                     INNER JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                     LEFT JOIN usuarios_sistema us ON us.persona_id = p.id
                     LEFT JOIN cat_roles crs ON us.rol_id = crs.id
                     WHERE {$whereClause}";
        $total = $this->db->fetchOne($sqlCount, $params)['total'] ?? 0;

        // Obtener registros
        $sql = "SELECT p.*, 
                       cpt.codigo as tipo_persona, 
                       cpt.nombre as tipo_persona_nombre,
                       us.id as usuario_sistema_id,
                       us.username,
                       crs.codigo as rol,
                       crs.nombre as rol_nombre
                FROM personas p
                INNER JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                LEFT JOIN usuarios_sistema us ON us.persona_id = p.id
                LEFT JOIN cat_roles crs ON us.rol_id = crs.id
                WHERE {$whereClause} 
                ORDER BY p.created_at DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->fetchAll($sql, $params);

        return [
            'data' => $data,
            'total' => (int)$total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Obtener usuario por ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT p.*, 
                       CONCAT(p.nombres, ' ', COALESCE(p.apellidos, '')) as nombre,
                       LOWER(cpt.codigo) as tipo_persona, 
                       cpt.nombre as tipo_persona_nombre,
                       us.id as usuario_sistema_id,
                       us.username,
                       COALESCE(us.email, p.email) as email,
                       LOWER(crs.codigo) as rol,
                       crs.nombre as rol_nombre
                FROM personas p
                JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                LEFT JOIN usuarios_sistema us ON us.persona_id = p.id
                LEFT JOIN cat_roles crs ON us.rol_id = crs.id
                WHERE p.id = :id AND p.deleted_at IS NULL 
                LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Obtener usuario por documento
     */
    public function findByDocument(string $documento): ?array
    {
        $sql = "SELECT p.*, 
                       cpt.codigo as tipo_persona, 
                       us.id as usuario_sistema_id,
                       us.username,
                       crs.codigo as rol
                FROM personas p
                JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                LEFT JOIN usuarios_sistema us ON us.persona_id = p.id
                LEFT JOIN cat_roles crs ON us.rol_id = crs.id
                WHERE p.documento = :documento AND p.deleted_at IS NULL 
                LIMIT 1";
        return $this->db->fetchOne($sql, ['documento' => $documento]);
    }

    /**
     * Crear nuevo usuario
     * 
     * @param array $data
     * @param int $createdBy ID del usuario que crea
     * @return int|false ID del usuario creado o false
     */
    public function create(array $data, int $createdBy): int|false
    {
        try {
            $this->connection->beginTransaction();

            // Insertar en personas
            $sqlPersona = "INSERT INTO personas (
                documento, tipo_documento, nombres, apellidos, tipo_persona_id, empresa, telefono, email, estado, created_at
            ) VALUES (
                :documento, :tipo_documento, :nombres, :apellidos, :tipo_persona_id, :empresa, :telefono, :email, :estado, NOW()
            )";

            // Dividir nombre completo en nombres y apellidos
            $partes = $this->dividirNombreCompleto($data['nombre']);

            $paramsPersona = [
                'documento' => strtoupper(trim($data['documento'])),
                'tipo_documento' => $data['tipo_documento'] ?? 'CC',
                'nombres' => $partes['nombres'],
                'apellidos' => $partes['apellidos'],
                'tipo_persona_id' => $data['tipo_persona'], // Ya viene convertido del controlador
                'empresa' => !empty($data['empresa']) ? trim($data['empresa']) : null,
                'telefono' => !empty($data['telefono']) ? trim($data['telefono']) : null,
                'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
            ];

            $stmt = $this->connection->prepare($sqlPersona);
            $stmt->execute($paramsPersona);
            $personaId = (int)$this->connection->lastInsertId();

            // Si tiene rol de sistema (admin, instructor, vigilante), crear usuario_sistema
            if (!empty($data['rol']) && in_array($data['rol'], ['admin', 'instructor', 'vigilante'])) {
                // Obtener rol_id del catÃ¡logo
                $sqlRol = "SELECT id FROM cat_roles WHERE codigo = :codigo";
                $stmtRol = $this->connection->prepare($sqlRol);
                $stmtRol->execute(['codigo' => strtoupper($data['rol'])]);
                $rolId = $stmtRol->fetchColumn();

                if (!$rolId) {
                    throw new Exception("Rol no encontrado: {$data['rol']}");
                }

                // Hash de password
                $passwordHash = null;
                if (!empty($data['password'])) {
                    $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
                } else {
                    throw new Exception("Password requerido para usuarios del sistema");
                }

                $sqlUsuario = "INSERT INTO usuarios_sistema (
                    persona_id, rol_id, username, email, password_hash, estado, created_at
                ) VALUES (
                    :persona_id, :rol_id, :username, :email, :password_hash, :estado, NOW()
                )";

                // Si no hay email, generar uno basado en el username
                $email = !empty($data['email']) ? strtolower(trim($data['email'])) : strtolower(trim($data['username'])) . '@sena.edu.co';

                $paramsUsuario = [
                    'persona_id' => $personaId,
                    'rol_id' => $rolId,
                    'username' => strtolower(trim($data['username'])),
                    'email' => $email,
                    'password_hash' => $passwordHash,
                    'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
                ];

                $stmt = $this->connection->prepare($sqlUsuario);
                $stmt->execute($paramsUsuario);
            }

            // AuditorÃ­a
            $this->logAudit($personaId, 'crear', $createdBy, null, $data);

            $this->connection->commit();
            return $personaId;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar usuario existente
     * 
     * @param int $id
     * @param array $data
     * @param int $updatedBy
     * @return bool
     */
    public function update(int $id, array $data, int $updatedBy): bool
    {
        try {
            $this->connection->beginTransaction();

            // Obtener datos anteriores para auditorÃ­a
            $oldData = $this->findById($id);
            if (!$oldData) {
                $this->connection->rollBack();
                return false;
            }

            // Actualizar personas
            $sqlPersona = "UPDATE personas SET 
                documento = :documento,
                nombres = :nombres,
                apellidos = :apellidos,
                tipo_persona_id = :tipo_persona_id,
                empresa = :empresa,
                telefono = :telefono,
                email = :email,
                estado = :estado,
                updated_at = NOW()
                WHERE id = :id AND deleted_at IS NULL";

            // Dividir nombre completo en nombres y apellidos
            $partes = $this->dividirNombreCompleto($data['nombre']);

            $paramsPersona = [
                'id' => $id,
                'documento' => strtoupper(trim($data['documento'])),
                'nombres' => $partes['nombres'],
                'apellidos' => $partes['apellidos'],
                'tipo_persona_id' => $data['tipo_persona'],
                'empresa' => !empty($data['empresa']) ? trim($data['empresa']) : null,
                'telefono' => !empty($data['telefono']) ? trim($data['telefono']) : null,
                'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
            ];

            $stmt = $this->connection->prepare($sqlPersona);
            $stmt->execute($paramsPersona);

            // Si tiene rol de sistema, actualizar o crear usuarios_sistema
            if (!empty($data['rol']) && in_array($data['rol'], ['admin', 'instructor', 'vigilante'])) {
                // Obtener rol_id del catÃ¡logo
                $sqlRol = "SELECT id FROM cat_roles WHERE codigo = :codigo";
                $stmtRol = $this->connection->prepare($sqlRol);
                $stmtRol->execute(['codigo' => strtoupper($data['rol'])]);
                $rolId = $stmtRol->fetchColumn();

                if (!$rolId) {
                    throw new Exception("Rol no encontrado: {$data['rol']}");
                }

                // Verificar si existe usuario_sistema
                $sqlCheck = "SELECT id FROM usuarios_sistema WHERE persona_id = :persona_id";
                $stmtCheck = $this->connection->prepare($sqlCheck);
                $stmtCheck->execute(['persona_id' => $id]);
                $usuarioSistemaId = $stmtCheck->fetchColumn();

                if ($usuarioSistemaId) {
                    // Actualizar usuario_sistema existente
                    $sqlUsuario = "UPDATE usuarios_sistema SET 
                        rol_id = :rol_id,
                        username = :username,
                        email = :email,
                        estado = :estado,
                        updated_at = NOW()
                        WHERE persona_id = :persona_id";

                    $paramsUsuario = [
                        'persona_id' => $id,
                        'rol_id' => $rolId,
                        'username' => strtolower(trim($data['username'])),
                        'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                        'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
                    ];

                    // Actualizar password solo si se proporciona
                    if (!empty($data['password'])) {
                        $sqlUsuario = "UPDATE usuarios_sistema SET 
                            rol_id = :rol_id,
                            username = :username,
                            email = :email,
                            password_hash = :password_hash,
                            estado = :estado,
                            updated_at = NOW()
                            WHERE persona_id = :persona_id";
                        $paramsUsuario['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
                    }

                    $stmt = $this->connection->prepare($sqlUsuario);
                    $stmt->execute($paramsUsuario);
                } else {
                    // Crear nuevo usuario_sistema
                    $passwordHash = null;
                    if (!empty($data['password'])) {
                        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
                    } else {
                        throw new Exception("Password requerido para crear usuario del sistema");
                    }

                    $sqlUsuario = "INSERT INTO usuarios_sistema (
                        persona_id, rol_id, username, email, password_hash, estado, created_at
                    ) VALUES (
                        :persona_id, :rol_id, :username, :email, :password_hash, :estado, NOW()
                    )";

                    $paramsUsuario = [
                        'persona_id' => $id,
                        'rol_id' => $rolId,
                        'username' => strtolower(trim($data['username'])),
                        'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                        'password_hash' => $passwordHash,
                        'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
                    ];

                    $stmt = $this->connection->prepare($sqlUsuario);
                    $stmt->execute($paramsUsuario);
                }
            }

            // AuditorÃ­a
            $this->logAudit($id, 'editar', $updatedBy, $oldData, $data);

            $this->connection->commit();
            return true;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cambiar estado de usuario (activar/desactivar)
     */
    public function toggleStatus(int $id, int $updatedBy): bool
    {
        try {
            $user = $this->findById($id);
            if (!$user) {
                return false;
            }

            $newStatus = strtoupper($user['estado']) === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
            $action = $newStatus === 'ACTIVO' ? 'activar' : 'desactivar';

            $this->connection->beginTransaction();

            // Actualizar personas
            $sql = "UPDATE personas SET estado = :estado, updated_at = NOW() 
                    WHERE id = :id AND deleted_at IS NULL";

            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute([
                'estado' => $newStatus,
                'id' => $id
            ]);

            // TambiÃ©n actualizar usuarios_sistema si existe
            $sqlUsuario = "UPDATE usuarios_sistema SET estado = :estado, updated_at = NOW() 
                          WHERE persona_id = :persona_id";
            $stmtUsuario = $this->connection->prepare($sqlUsuario);
            $stmtUsuario->execute([
                'estado' => $newStatus,
                'persona_id' => $id
            ]);

            if ($result) {
                $this->logAudit($id, $action, $updatedBy, ['estado' => $user['estado']], ['estado' => $newStatus]);
            }

            $this->connection->commit();

            return $result;

        } catch (Exception $e) {
            error_log("Error al cambiar estado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar usuario (borrado lÃ³gico)
     */
    public function delete(int $id, int $deletedBy): bool
    {
        try {
            $this->connection->beginTransaction();

            $user = $this->findById($id);
            if (!$user) {
                $this->connection->rollBack();
                return false;
            }

            $sql = "UPDATE personas SET deleted_at = NOW() 
                    WHERE id = :id AND deleted_at IS NULL";

            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute([
                'id' => $id
            ]);

            if ($result) {
                $this->logAudit($id, 'eliminar', $deletedBy, $user, null);
            }

            $this->connection->commit();
            return $result;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Dividir nombre completo en nombres y apellidos
     * Ejemplo: "Ana Sofia López Montes" -> nombres: "Ana Sofia", apellidos: "López Montes"
     */
    private function dividirNombreCompleto(string $nombreCompleto): array
    {
        $nombreCompleto = trim($nombreCompleto);
        $partes = explode(' ', $nombreCompleto);
        $cantidadPartes = count($partes);

        if ($cantidadPartes <= 1) {
            // Solo un nombre
            return [
                'nombres' => $nombreCompleto,
                'apellidos' => null
            ];
        } elseif ($cantidadPartes == 2) {
            // Un nombre y un apellido
            return [
                'nombres' => $partes[0],
                'apellidos' => $partes[1]
            ];
        } else {
            // Múltiples partes: últimas 2 son apellidos, resto son nombres
            $apellidos = array_slice($partes, -2);
            $nombres = array_slice($partes, 0, -2);
            return [
                'nombres' => implode(' ', $nombres),
                'apellidos' => implode(' ', $apellidos)
            ];
        }
    }

    /**
     * Registrar auditoría de cambios (opcional - tabla auditoria no existe aún)
     */
    private function logAudit(int $userId, string $action, int $executorId, ?array $oldData, ?array $newData): void
    {
        // TODO: Implementar cuando la tabla auditoria esté creada
        // Por ahora solo registramos en el log de PHP
        try {
            error_log(sprintf(
                "AUDIT: Usuario %d ejecutó '%s' en usuario %d",
                $executorId,
                $action,
                $userId
            ));
        } catch (Exception $e) {
            // Silenciar errores de auditoría para no bloquear operaciones
            error_log("Error en auditoría: " . $e->getMessage());
        }
    }

    /**
     * Obtener estadÃ­sticas de usuarios
     */
    public function getStats(): array
    {
        $stats = [
            'total' => 0,
            'activos' => 0,
            'inactivos' => 0,
            'por_tipo' => []
        ];

        // Total y por estado
        $sql = "SELECT p.estado, COUNT(*) as count 
                FROM personas p 
                WHERE p.deleted_at IS NULL 
                GROUP BY p.estado";
        $results = $this->db->fetchAll($sql);
        
        foreach ($results as $row) {
            $stats['total'] += $row['count'];
            if ($row['estado'] === 'ACTIVO') {
                $stats['activos'] = (int)$row['count'];
            } else {
                $stats['inactivos'] = (int)$row['count'];
            }
        }

        // Por tipo de persona
        $sql = "SELECT cpt.codigo, cpt.nombre, COUNT(*) as count 
                FROM personas p
                JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                WHERE p.deleted_at IS NULL 
                GROUP BY cpt.codigo, cpt.nombre";
        $results = $this->db->fetchAll($sql);
        
        foreach ($results as $row) {
            $stats['por_tipo'][$row['codigo']] = (int)$row['count'];
        }

        return $stats;
    }

    // ========================================
    // IMPORTACIÃ“N MASIVA
    // ========================================

    /**
     * Procesar archivo CSV y retornar vista previa
     * 
     * @param string $filePath Ruta temporal del archivo
     * @param array $options ['has_header' => true, 'delimiter' => ',', 'mode' => 'insert|upsert']
     * @return array ['preview' => [...], 'errors' => [...], 'total' => int, 'valid' => int]
     */
    public function previewImport(string $filePath, array $options = []): array
    {
        $hasHeader = $options['has_header'] ?? true;
        $delimiter = $options['delimiter'] ?? ',';
        $mode = $options['mode'] ?? 'upsert';

        $preview = [];
        $errors = [];
        $lineNumber = 0;
        $validRows = 0;

        if (!file_exists($filePath)) {
            return ['error' => 'Archivo no encontrado', 'preview' => [], 'errors' => [], 'total' => 0, 'valid' => 0];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['error' => 'No se pudo abrir el archivo', 'preview' => [], 'errors' => [], 'total' => 0, 'valid' => 0];
        }

        // Leer header
        $headers = [];
        if ($hasHeader) {
            $headers = fgetcsv($handle, 1000, $delimiter);
            $lineNumber++;
        } else {
            // Headers por defecto
            $headers = ['documento', 'nombre', 'tipo_persona', 'empresa', 'email', 'username'];
        }

        // Validar headers mÃ­nimos
        $requiredHeaders = ['documento', 'nombre', 'tipo_persona'];
        $missingHeaders = array_diff($requiredHeaders, $headers);
        if (!empty($missingHeaders)) {
            fclose($handle);
            return [
                'error' => 'Faltan columnas requeridas: ' . implode(', ', $missingHeaders),
                'preview' => [],
                'errors' => [],
                'total' => 0,
                'valid' => 0
            ];
        }

        // Leer hasta 100 filas para preview (mostrar solo 20 en vista)
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false && $lineNumber < 100) {
            $lineNumber++;

            // Combinar headers con datos
            $row = array_combine($headers, array_pad($data, count($headers), ''));

            // Validar fila
            $rowErrors = $this->validateImportRow($row, $mode);

            if (empty($rowErrors)) {
                $validRows++;
                $row['_status'] = 'valid';
            } else {
                $row['_status'] = 'error';
                $errors[] = [
                    'line' => $lineNumber,
                    'errors' => $rowErrors,
                    'data' => $row
                ];
            }

            $preview[] = $row;
        }

        // Contar total de filas restantes
        $totalRows = $lineNumber;
        while (fgets($handle) !== false) {
            $totalRows++;
        }

        fclose($handle);

        return [
            'preview' => array_slice($preview, 0, 20), // Solo 20 para mostrar en UI
            'errors' => array_slice($errors, 0, 50), // Solo 50 primeros errores
            'total' => $totalRows - ($hasHeader ? 1 : 0),
            'valid' => $validRows,
            'headers' => $headers
        ];
    }

    /**
     * Validar fila de importaciÃ³n
     */
    private function validateImportRow(array $row, string $mode): array
    {
        $errors = [];

        // Documento requerido y Ãºnico
        if (empty($row['documento'])) {
            $errors[] = 'Documento es obligatorio';
        } elseif (!preg_match('/^[A-Z0-9]{6,20}$/i', $row['documento'])) {
            $errors[] = 'Documento invÃ¡lido (6-20 caracteres alfanumÃ©ricos)';
        } else {
            // Verificar si existe
            $existing = $this->findByDocument(strtoupper(trim($row['documento'])));
            if ($existing && $mode === 'insert') {
                $errors[] = 'Documento ya existe (modo: solo insertar)';
            }
        }

        // Nombre requerido
        if (empty($row['nombre'])) {
            $errors[] = 'Nombre es obligatorio';
        } elseif (strlen($row['nombre']) < 3 || strlen($row['nombre']) > 100) {
            $errors[] = 'Nombre debe tener entre 3 y 100 caracteres';
        }

        // Tipo persona requerido
        $tiposValidos = ['admin', 'instructor', 'vigilante', 'aprendiz', 'contratista', 'visitante', 'proveedor'];
        if (empty($row['tipo_persona'])) {
            $errors[] = 'Tipo de persona es obligatorio';
        } elseif (!in_array($row['tipo_persona'], $tiposValidos)) {
            $errors[] = 'Tipo de persona invÃ¡lido: ' . implode(', ', $tiposValidos);
        }

        // Email opcional pero debe ser vÃ¡lido
        if (!empty($row['email']) && !filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invÃ¡lido';
        }

        return $errors;
    }

    /**
     * Ejecutar importaciÃ³n confirmada
     * 
     * @param string $filePath
     * @param array $options
     * @param int $userId Usuario que ejecuta
     * @return array ['insertados' => int, 'actualizados' => int, 'omitidos' => int, 'errores' => array]
     */
    public function executeImport(string $filePath, array $options, int $userId): array
    {
        $hasHeader = $options['has_header'] ?? true;
        $delimiter = $options['delimiter'] ?? ',';
        $mode = $options['mode'] ?? 'upsert';

        $stats = [
            'insertados' => 0,
            'actualizados' => 0,
            'omitidos' => 0,
            'errores' => []
        ];

        if (!file_exists($filePath)) {
            $stats['errores'][] = ['line' => 0, 'error' => 'Archivo no encontrado'];
            return $stats;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $stats['errores'][] = ['line' => 0, 'error' => 'No se pudo abrir el archivo'];
            return $stats;
        }

        $lineNumber = 0;
        $headers = [];

        // Leer header
        if ($hasHeader) {
            $headers = fgetcsv($handle, 1000, $delimiter);
            $lineNumber++;
        } else {
            $headers = ['documento', 'nombre', 'tipo_persona', 'empresa', 'email', 'username'];
        }

        // Procesar filas
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $lineNumber++;

            try {
                $row = array_combine($headers, array_pad($data, count($headers), ''));

                // Validar
                $rowErrors = $this->validateImportRow($row, $mode);
                if (!empty($rowErrors)) {
                    $stats['omitidos']++;
                    $stats['errores'][] = [
                        'line' => $lineNumber,
                        'errors' => $rowErrors,
                        'data' => $row
                    ];
                    continue;
                }

                // Preparar datos
                $userData = [
                    'documento' => strtoupper(trim($row['documento'])),
                    'nombre' => trim($row['nombre']),
                    'tipo_persona' => $row['tipo_persona'],
                    'empresa' => !empty($row['empresa']) ? trim($row['empresa']) : null,
                    'email' => !empty($row['email']) ? strtolower(trim($row['email'])) : null,
                    'username' => !empty($row['username']) ? strtolower(trim($row['username'])) : null,
                    'rol' => in_array($row['tipo_persona'], ['admin', 'instructor', 'vigilante']) ? $row['tipo_persona'] : 'persona',
                    'estado' => 'activo'
                ];

                // Verificar si existe
                $existing = $this->findByDocument($userData['documento']);

                if ($existing) {
                    if ($mode === 'upsert') {
                        // Actualizar
                        if ($this->update($existing['id'], $userData, $userId)) {
                            $stats['actualizados']++;
                        } else {
                            $stats['errores'][] = ['line' => $lineNumber, 'error' => 'Error al actualizar'];
                        }
                    } else {
                        // Modo insert: omitir duplicados
                        $stats['omitidos']++;
                    }
                } else {
                    // Insertar
                    if ($this->create($userData, $userId)) {
                        $stats['insertados']++;
                    } else {
                        $stats['errores'][] = ['line' => $lineNumber, 'error' => 'Error al insertar'];
                    }
                }

            } catch (Exception $e) {
                $stats['errores'][] = [
                    'line' => $lineNumber,
                    'error' => $e->getMessage()
                ];
            }
        }

        fclose($handle);

        // Registrar importaciÃ³n
        $this->logImport($userId, basename($filePath), $stats);

        return $stats;
    }

    /**
     * Registrar importaciÃ³n en historial
     */
    private function logImport(int $userId, string $filename, array $stats): void
    {
        try {
            $total = $stats['insertados'] + $stats['actualizados'] + $stats['omitidos'];
            $errores = count($stats['errores']);

            $sql = "INSERT INTO importaciones (archivo_nombre, tipo, usuario_id, total_filas, insertados, actualizados, omitidos, errores, estado, log_errores, completed_at)
                    VALUES (:filename, 'usuarios', :user_id, :total, :inserted, :updated, :skipped, :errors, 'completado', :log, NOW())";

            $this->db->query($sql, [
                'filename' => $filename,
                'user_id' => $userId,
                'total' => $total,
                'inserted' => $stats['insertados'],
                'updated' => $stats['actualizados'],
                'skipped' => $stats['omitidos'],
                'errors' => $errores,
                'log' => json_encode($stats['errores'], JSON_UNESCAPED_UNICODE)
            ]);
        } catch (Exception $e) {
            error_log("Error al registrar importación: " . $e->getMessage());
        }
    }
}

