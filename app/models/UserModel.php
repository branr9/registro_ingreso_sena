<?php
/**
 * Modelo UserModel - CRUD completo de usuarios
 * Extiende funcionalidad de User.php (autenticación)
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
     * Listar usuarios con filtros y paginación
     */
    public function paginate(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $where = ['p.deleted_at IS NULL'];
        $params = [];

        if (!empty($filters['search'])) {
            $searchValue = '%' . $filters['search'] . '%';
            $where[] = '(p.documento LIKE :search1 OR p.nombres LIKE :search2 OR COALESCE(p.apellidos, "") LIKE :search3 OR COALESCE(p.email, "") LIKE :search4 OR COALESCE(us.username, "") LIKE :search5)';
            $params['search1'] = $searchValue;
            $params['search2'] = $searchValue;
            $params['search3'] = $searchValue;
            $params['search4'] = $searchValue;
            $params['search5'] = $searchValue;
        }

        if (!empty($filters['tipo_persona'])) {
            $where[] = 'cpt.codigo = :tipo_persona';
            $params['tipo_persona'] = $filters['tipo_persona'];
        }

        if (!empty($filters['estado'])) {
            $where[] = 'p.estado = :estado';
            $params['estado'] = $filters['estado'];
        }

        if (!empty($filters['rol'])) {
            $where[] = 'crs.codigo = :rol';
            $params['rol'] = $filters['rol'];
        }

        $whereClause = implode(' AND ', $where);

        $sqlCount = "SELECT COUNT(*) as total 
                     FROM personas p
                     INNER JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                     LEFT JOIN usuarios_sistema us ON us.persona_id = p.id
                     LEFT JOIN cat_roles crs ON us.rol_id = crs.id
                     WHERE {$whereClause}";
        $total = $this->db->fetchOne($sqlCount, $params)['total'] ?? 0;

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
    public function findById(int $id, bool $includeDeleted = false): ?array
    {
        $deletedCondition = $includeDeleted ? '' : ' AND p.deleted_at IS NULL';
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
                WHERE p.id = :id{$deletedCondition}
                LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Obtener usuario por documento
     */
    public function findByDocument(string $documento, bool $includeDeleted = false): ?array
    {
        $deletedCondition = $includeDeleted ? '' : ' AND p.deleted_at IS NULL';
        $sql = "SELECT p.*, 
                       cpt.codigo as tipo_persona, 
                       us.id as usuario_sistema_id,
                       us.username,
                       crs.codigo as rol
                FROM personas p
                JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                LEFT JOIN usuarios_sistema us ON us.persona_id = p.id
                LEFT JOIN cat_roles crs ON us.rol_id = crs.id
                WHERE p.documento = :documento{$deletedCondition}
                LIMIT 1";
        return $this->db->fetchOne($sql, ['documento' => $documento]);
    }

    /**
     * Crear nuevo usuario
     */
    public function create(array $data, int $createdBy): int|false
    {
        try {
            $this->connection->beginTransaction();

            $sqlPersona = "INSERT INTO personas (
                documento, tipo_documento, nombres, apellidos, tipo_persona_id, empresa, telefono, email, estado, created_at
            ) VALUES (
                :documento, :tipo_documento, :nombres, :apellidos, :tipo_persona_id, :empresa, :telefono, :email, :estado, NOW()
            )";

            $partes = $this->dividirNombreCompleto($data['nombre']);

            $paramsPersona = [
                'documento' => strtoupper(trim($data['documento'])),
                'tipo_documento' => $data['tipo_documento'] ?? 'CC',
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
            $personaId = (int)$this->connection->lastInsertId();

            $rolLower = strtolower($data['rol'] ?? '');
            if (!empty($rolLower) && in_array($rolLower, ['admin', 'administrador', 'instructor', 'vigilante'])) {
                
                $rolBuscado = ($rolLower === 'administrador') ? 'ADMIN' : strtoupper($rolLower);
                $sqlRol = "SELECT id FROM cat_roles WHERE codigo = :codigo";
                $stmtRol = $this->connection->prepare($sqlRol);
                $stmtRol->execute(['codigo' => $rolBuscado]);
                $rolId = $stmtRol->fetchColumn();

                if (!$rolId) {
                    $stmtRol->execute(['codigo' => 'PERSONA']);
                    $rolId = $stmtRol->fetchColumn();
                }

                if ($rolId) {
                    $passwordHash = null;
                    if (!empty($data['password'])) {
                        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
                    } elseif (!empty($data['documento'])) {
                        $passwordHash = password_hash(trim($data['documento']), PASSWORD_BCRYPT);
                    } else {
                        $passwordHash = password_hash('12345678', PASSWORD_BCRYPT);
                    }

                    $sqlUsuario = "INSERT INTO usuarios_sistema (
                        persona_id, rol_id, username, email, password_hash, estado, created_at
                    ) VALUES (
                        :persona_id, :rol_id, :username, :email, :password_hash, :estado, NOW()
                    )";

                    $email = !empty($data['email']) ? strtolower(trim($data['email'])) : strtolower(trim($data['username'] ?? $data['documento'])) . '@sena.edu.co';

                    $paramsUsuario = [
                        'persona_id' => $personaId,
                        'rol_id' => $rolId,
                        'username' => strtolower(trim($data['username'] ?? $data['documento'])),
                        'email' => $email,
                        'password_hash' => $passwordHash,
                        'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
                    ];

                    $stmt = $this->connection->prepare($sqlUsuario);
                    $stmt->execute($paramsUsuario);
                }
            }

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
     */
    public function update(int $id, array $data, int $updatedBy, bool $restoreDeleted = false): bool
    {
        try {
            $this->connection->beginTransaction();

            $oldData = $this->findById($id, $restoreDeleted);
            if (!$oldData) {
                $this->connection->rollBack();
                return false;
            }

            $restoreField = $restoreDeleted ? "deleted_at = NULL," : '';
            $restoreCondition = $restoreDeleted ? '' : ' AND deleted_at IS NULL';
            $sqlPersona = "UPDATE personas SET 
                documento = :documento,
                nombres = :nombres,
                apellidos = :apellidos,
                tipo_persona_id = :tipo_persona_id,
                empresa = :empresa,
                telefono = :telefono,
                email = :email,
                estado = :estado,
                {$restoreField}
                updated_at = NOW()
                WHERE id = :id{$restoreCondition}";

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

            $rolLower = strtolower($data['rol'] ?? '');
            if (!empty($rolLower) && in_array($rolLower, ['admin', 'administrador', 'instructor', 'vigilante'])) {
                
                $rolBuscado = ($rolLower === 'administrador') ? 'ADMIN' : strtoupper($rolLower);
                $sqlRol = "SELECT id FROM cat_roles WHERE codigo = :codigo";
                $stmtRol = $this->connection->prepare($sqlRol);
                $stmtRol->execute(['codigo' => $rolBuscado]);
                $rolId = $stmtRol->fetchColumn();

                if (!$rolId) {
                    $stmtRol->execute(['codigo' => 'PERSONA']);
                    $rolId = $stmtRol->fetchColumn();
                }

                if ($rolId) {
                    $sqlCheck = "SELECT id FROM usuarios_sistema WHERE persona_id = :persona_id";
                    $stmtCheck = $this->connection->prepare($sqlCheck);
                    $stmtCheck->execute(['persona_id' => $id]);
                    $usuarioSistemaId = $stmtCheck->fetchColumn();

                    if ($usuarioSistemaId) {
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
                            'username' => strtolower(trim($data['username'] ?? $data['documento'])),
                            'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                            'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
                        ];

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
                        $passwordHash = null;
                        if (!empty($data['password'])) {
                            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
                        } elseif (!empty($data['documento'])) {
                            $passwordHash = password_hash(trim($data['documento']), PASSWORD_BCRYPT);
                        } else {
                            $passwordHash = password_hash('12345678', PASSWORD_BCRYPT);
                        }

                        $sqlUsuario = "INSERT INTO usuarios_sistema (
                            persona_id, rol_id, username, email, password_hash, estado, created_at
                        ) VALUES (
                            :persona_id, :rol_id, :username, :email, :password_hash, :estado, NOW()
                        )";

                        $paramsUsuario = [
                            'persona_id' => $id,
                            'rol_id' => $rolId,
                            'username' => strtolower(trim($data['username'] ?? $data['documento'])),
                            'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                            'password_hash' => $passwordHash,
                            'estado' => strtoupper($data['estado'] ?? 'ACTIVO')
                        ];

                        $stmt = $this->connection->prepare($sqlUsuario);
                        $stmt->execute($paramsUsuario);
                    }
                }
            }

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

            $sql = "UPDATE personas SET estado = :estado, updated_at = NOW() 
                    WHERE id = :id AND deleted_at IS NULL";
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute(['estado' => $newStatus, 'id' => $id]);

            $sqlUsuario = "UPDATE usuarios_sistema SET estado = :estado, updated_at = NOW() 
                          WHERE persona_id = :persona_id";
            $stmtUsuario = $this->connection->prepare($sqlUsuario);
            $stmtUsuario->execute(['estado' => $newStatus, 'persona_id' => $id]);

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
     * Eliminar usuario (borrado lógico)
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
            $result = $stmt->execute(['id' => $id]);

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

    private function dividirNombreCompleto(string $nombreCompleto): array
    {
        $nombreCompleto = trim($nombreCompleto);
        $partes = explode(' ', $nombreCompleto);
        $cantidadPartes = count($partes);

        if ($cantidadPartes <= 1) {
            return ['nombres' => $nombreCompleto, 'apellidos' => null];
        } elseif ($cantidadPartes == 2) {
            return ['nombres' => $partes[0], 'apellidos' => $partes[1]];
        } else {
            $apellidos = array_slice($partes, -2);
            $nombres = array_slice($partes, 0, -2);
            return [
                'nombres' => implode(' ', $nombres),
                'apellidos' => implode(' ', $apellidos)
            ];
        }
    }

    private function logAudit(int $userId, string $action, int $executorId, ?array $oldData, ?array $newData): void
    {
        try {
            error_log(sprintf("AUDIT: Usuario %d ejecutó '%s' en usuario %d", $executorId, $action, $userId));
        } catch (Exception $e) {
            error_log("Error en auditoría: " . $e->getMessage());
        }
    }

    public function getStats(): array
    {
        $stats = [
            'total' => 0, 'activos' => 0, 'inactivos' => 0, 'por_tipo' => []
        ];

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
    // IMPORTACIÓN MASIVA MEJORADA
    // ========================================

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

        $headers = [];
        if ($hasHeader) {
            $headers = fgetcsv($handle, 1000, $delimiter);
            $headers = $this->normalizeImportHeaders($headers ?: []);
            $lineNumber++;
        } else {
            $headers = ['documento', 'nombre', 'tipo_persona', 'empresa', 'email', 'username'];
        }

        $requiredHeaders = ['documento', 'nombre', 'tipo_persona'];
        $missingHeaders = array_diff($requiredHeaders, $headers);
        if (!empty($missingHeaders)) {
            fclose($handle);
            return [
                'error' => 'Faltan columnas requeridas: ' . implode(', ', $missingHeaders),
                'preview' => [], 'errors' => [], 'total' => 0, 'valid' => 0
            ];
        }

        $isFirstDataRow = true;
        $previewRowsRead = 0;
        while ($previewRowsRead < 100 && ($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $lineNumber++;
            $previewRowsRead++;

            // Ignorar filas vacías, frecuentes al guardar archivos CSV desde Excel.
            if ($this->isEmptyImportRow($data)) {
                continue;
            }
            
            if ($isFirstDataRow && !$hasHeader) {
                 $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0] ?? ''); // Limpiar BOM
            }
            $isFirstDataRow = false;

            $row = array_combine($headers, array_pad($data, count($headers), ''));
            $rowErrors = $this->validateImportRow($row, $mode);

            if (empty($rowErrors)) {
                $validRows++;
                $row['_status'] = 'valid';
            } else {
                $row['_status'] = 'error';
                $errors[] = ['line' => $lineNumber, 'errors' => $rowErrors, 'data' => $row];
            }
            $preview[] = $row;
        }

        $totalRows = $lineNumber;
        while (fgets($handle) !== false) {
            $totalRows++;
        }
        fclose($handle);

        return [
            'preview' => array_slice($preview, 0, 20),
            'errors' => array_slice($errors, 0, 50),
            'total' => $totalRows - ($hasHeader ? 1 : 0),
            'valid' => $validRows,
            'headers' => $headers
        ];
    }

    private function validateImportRow(array &$row, string $mode): array
    {
        $errors = [];

        // Limpiar BOM y espacios
        $row['documento'] = preg_replace('/^\xEF\xBB\xBF/', '', trim($row['documento'] ?? ''));

        if (empty($row['documento'])) {
            $errors[] = 'Documento es obligatorio';
        } elseif (!preg_match('/^[A-Z0-9]{5,20}$/i', $row['documento'])) {
            $errors[] = 'Documento inválido (5-20 caracteres)';
        } else {
            $existing = $this->findByDocument(strtoupper($row['documento']), true);
            if ($existing && $mode === 'insert') {
                $errors[] = 'Documento ya existe (modo: solo insertar)';
            }
        }

        if (empty($row['nombre'])) {
            $errors[] = 'Nombre es obligatorio';
        } elseif (strlen(trim($row['nombre'])) < 3 || strlen(trim($row['nombre'])) > 100) {
            $errors[] = 'Nombre debe tener entre 3 y 100 caracteres';
        }

        $tiposValidos = ['admin', 'administrador', 'instructor', 'vigilante', 'aprendiz', 'contratista', 'visitante', 'proveedor', 'persona'];
        $tipoStr = strtolower(trim($row['tipo_persona'] ?? ''));
        
        if (empty($tipoStr)) {
            $errors[] = 'Tipo de persona es obligatorio';
        } elseif (!in_array($tipoStr, $tiposValidos)) {
            $errors[] = 'Tipo de persona inválido: ' . implode(', ', $tiposValidos);
        }

        if (!empty($row['email']) && !filter_var(trim($row['email']), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }

        return $errors;
    }

    public function executeImport(string $filePath, array $options, int $userId): array
    {
        $hasHeader = $options['has_header'] ?? true;
        $delimiter = $options['delimiter'] ?? ',';
        $mode = $options['mode'] ?? 'upsert';

        $stats = ['insertados' => 0, 'actualizados' => 0, 'omitidos' => 0, 'errores' => []];

        if (!file_exists($filePath)) {
            $stats['errores'][] = ['line' => 0, 'error' => 'Archivo no encontrado'];
            return $stats;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $stats['errores'][] = ['line' => 0, 'error' => 'No se pudo abrir el archivo'];
            return $stats;
        }

        // Cargar mapeo flexible
        $tiposMapeo = [];
        try {
            $sqlTipos = "SELECT id, LOWER(codigo) as codigo, LOWER(nombre) as nombre
                         FROM cat_persona_tipo
                         WHERE activo = 1";
            $tiposDb = $this->db->fetchAll($sqlTipos);
            foreach ($tiposDb as $t) {
                $tiposMapeo[$t['codigo']] = $t['id'];
                $tiposMapeo[$t['nombre']] = $t['id'];
            }
        } catch (Exception $e) {
            // Ignorar para no frenar proceso
        }

        $lineNumber = 0;
        $headers = [];

        if ($hasHeader) {
            $headers = fgetcsv($handle, 1000, $delimiter);
            $headers = $this->normalizeImportHeaders($headers ?: []);
            $lineNumber++;
        } else {
            $headers = ['documento', 'nombre', 'tipo_persona', 'empresa', 'email', 'username'];
        }

        $isFirstDataRow = true;
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $lineNumber++;

            if ($this->isEmptyImportRow($data)) {
                continue;
            }

            if ($isFirstDataRow && !$hasHeader) {
                 $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0] ?? '');
            }
            $isFirstDataRow = false;

            try {
                $row = array_combine($headers, array_slice(array_pad($data, count($headers), ''), 0, count($headers)));

                $rowErrors = $this->validateImportRow($row, $mode);
                if (!empty($rowErrors)) {
                    $stats['omitidos']++;
                    $stats['errores'][] = ['line' => $lineNumber, 'errors' => $rowErrors, 'data' => $row];
                    continue;
                }

                // El código de la plantilla debe existir en el catálogo. No se
                // asigna un tipo arbitrario: eso dejaba, por ejemplo, visitantes
                // registrados como administradores cuando faltaba el catálogo.
                $tipoString = strtolower(trim($row['tipo_persona']));
                $tipoPersonaId = $tiposMapeo[$tipoString] ?? null;

                if (!$tipoPersonaId) {
                    foreach ($tiposMapeo as $key => $id) {
                        if (strpos($key, $tipoString) !== false || strpos($tipoString, $key) !== false) {
                            $tipoPersonaId = $id; break;
                        }
                    }
                }
                
                if (!$tipoPersonaId) {
                    $stats['omitidos']++;
                    $stats['errores'][] = [
                        'line' => $lineNumber,
                        'error' => "El tipo de persona '{$row['tipo_persona']}' no está configurado en la base de datos"
                    ];
                    continue;
                }

                $userData = [
                    'documento' => strtoupper(trim($row['documento'])),
                    'nombre' => trim($row['nombre']),
                    'tipo_persona' => $tipoPersonaId, 
                    'empresa' => !empty($row['empresa']) ? trim($row['empresa']) : null,
                    'email' => !empty($row['email']) ? strtolower(trim($row['email'])) : null,
                    'username' => !empty($row['username']) ? strtolower(trim($row['username'])) : null,
                    'rol' => in_array($tipoString, ['admin', 'administrador', 'instructor', 'vigilante']) ? $tipoString : 'persona',
                    'estado' => 'activo',
                    'password' => trim($row['documento']) 
                ];

                // Incluir eliminados lógicos: con UPSERT se restauran para no
                // chocar con la restricción única de documento.
                $existing = $this->findByDocument($userData['documento'], true);

                if ($existing) {
                    if ($mode === 'upsert') {
                        unset($userData['password']); 
                        if ($this->update($existing['id'], $userData, $userId, !empty($existing['deleted_at']))) {
                            $stats['actualizados']++;
                        } else {
                            $stats['errores'][] = ['line' => $lineNumber, 'error' => 'Error al actualizar'];
                        }
                    } else {
                        $stats['omitidos']++;
                    }
                } else {
                    if ($this->create($userData, $userId)) {
                        $stats['insertados']++;
                    } else {
                        $stats['errores'][] = ['line' => $lineNumber, 'error' => 'Error al insertar'];
                    }
                }

            } catch (Exception $e) {
                $stats['errores'][] = ['line' => $lineNumber, 'error' => $e->getMessage()];
            }
        }

        fclose($handle);
        $this->logImport($userId, basename($filePath), $stats);

        return $stats;
    }

    private function logImport(int $userId, string $filename, array $stats): void
    {
        try {
            $this->ensureImportLogTable();
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

    /** Normaliza encabezados de CSV (BOM, espacios y mayúsculas). */
    private function normalizeImportHeaders(array $headers): array
    {
        return array_map(
            static fn ($header) => strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string)$header))),
            $headers
        );
    }

    /** Determina si una fila no contiene ningún dato útil. */
    private function isEmptyImportRow(array $data): bool
    {
        foreach ($data as $value) {
            if (trim((string)$value) !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Algunas instalaciones existentes no tenían la tabla de auditoría de
     * importaciones. La crea de forma compatible para que el registro no
     * provoque un error después de importar correctamente los usuarios.
     */
    private function ensureImportLogTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS importaciones (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    archivo_nombre VARCHAR(255) NOT NULL,
                    tipo VARCHAR(30) NOT NULL,
                    usuario_id BIGINT UNSIGNED NOT NULL,
                    total_filas INT UNSIGNED NOT NULL DEFAULT 0,
                    insertados INT UNSIGNED NOT NULL DEFAULT 0,
                    actualizados INT UNSIGNED NOT NULL DEFAULT 0,
                    omitidos INT UNSIGNED NOT NULL DEFAULT 0,
                    errores INT UNSIGNED NOT NULL DEFAULT 0,
                    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
                    log_errores LONGTEXT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    completed_at DATETIME NULL,
                    INDEX idx_importaciones_usuario (usuario_id),
                    INDEX idx_importaciones_fecha (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->query($sql);
    }
}
