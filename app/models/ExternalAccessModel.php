<?php
/**
 * Modelo: Registros de Acceso Personal Externo
 * Gestiona el registro de entrada y salida de personal sin carnet
 */

class ExternalAccessModel
{
    private PDO $connection;
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
    }

    /**
     * Listar registros con paginación y filtros
     */
    public function paginate(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $offset = ($page - 1) * $perPage;
        $where = ['1=1'];
        $params = [];

        // Filtro por búsqueda (documento, nombre, empresa)
        if (!empty($filters['search'])) {
            $searchValue = '%' . $filters['search'] . '%';
            $where[] = '(rae.documento LIKE :search1 
                        OR rae.nombres LIKE :search2 
                        OR rae.apellidos LIKE :search3
                        OR rae.empresa LIKE :search4)';
            $params['search1'] = $searchValue;
            $params['search2'] = $searchValue;
            $params['search3'] = $searchValue;
            $params['search4'] = $searchValue;
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $where[] = 'rae.estado = :estado';
            $params['estado'] = strtoupper($filters['estado']);
        }

        // Filtro por fecha
        if (!empty($filters['fecha_desde'])) {
            $where[] = 'DATE(rae.fecha_entrada) >= :fecha_desde';
            $params['fecha_desde'] = $filters['fecha_desde'];
        }

        if (!empty($filters['fecha_hasta'])) {
            $where[] = 'DATE(rae.fecha_entrada) <= :fecha_hasta';
            $params['fecha_hasta'] = $filters['fecha_hasta'];
        }

        $whereClause = implode(' AND ', $where);

        // Contar total
        $sqlCount = "SELECT COUNT(*) as total FROM registros_acceso_externo rae WHERE {$whereClause}";
        $total = $this->db->fetchOne($sqlCount, $params)['total'] ?? 0;

        // Obtener registros
        $sql = "SELECT * FROM vista_acceso_externo WHERE {$whereClause} LIMIT {$perPage} OFFSET {$offset}";
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
     * Obtener registro por ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM vista_acceso_externo WHERE id = :id LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Registrar entrada de personal externo
     */
    public function registrarEntrada(array $data, int $vigilanteId): int|false
    {
        try {
            $sql = "INSERT INTO registros_acceso_externo (
                documento, tipo_documento, nombres, apellidos, empresa, telefono, email,
                motivo_visita, persona_visitada, area_destino,
                fecha_entrada, vigilante_entrada_id, observaciones, estado
            ) VALUES (
                :documento, :tipo_documento, :nombres, :apellidos, :empresa, :telefono, :email,
                :motivo_visita, :persona_visitada, :area_destino,
                NOW(), :vigilante_entrada_id, :observaciones, 'DENTRO'
            )";

            $params = [
                'documento' => strtoupper(trim($data['documento'])),
                'tipo_documento' => $data['tipo_documento'] ?? 'CC',
                'nombres' => trim($data['nombres']),
                'apellidos' => !empty($data['apellidos']) ? trim($data['apellidos']) : null,
                'empresa' => !empty($data['empresa']) ? trim($data['empresa']) : null,
                'telefono' => !empty($data['telefono']) ? trim($data['telefono']) : null,
                'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                'motivo_visita' => trim($data['motivo_visita']),
                'persona_visitada' => !empty($data['persona_visitada']) ? trim($data['persona_visitada']) : null,
                'area_destino' => !empty($data['area_destino']) ? trim($data['area_destino']) : null,
                'vigilante_entrada_id' => $vigilanteId,
                'observaciones' => !empty($data['observaciones']) ? trim($data['observaciones']) : null
            ];

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            
            return (int)$this->connection->lastInsertId();

        } catch (Exception $e) {
            error_log("Error al registrar entrada: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar salida de personal externo
     */
    public function registrarSalida(int $id, int $vigilanteId, ?string $observaciones = null): bool
    {
        try {
            $sql = "UPDATE registros_acceso_externo SET 
                fecha_salida = NOW(),
                tiempo_permanencia = TIMESTAMPDIFF(MINUTE, fecha_entrada, NOW()),
                vigilante_salida_id = :vigilante_salida_id,
                observaciones = CONCAT(COALESCE(observaciones, ''), :observaciones),
                estado = 'SALIO'
                WHERE id = :id AND estado = 'DENTRO'";

            $params = [
                'id' => $id,
                'vigilante_salida_id' => $vigilanteId,
                'observaciones' => $observaciones ? "\nSalida: " . $observaciones : ''
            ];

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            error_log("Error al registrar salida: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener personas actualmente dentro de las instalaciones
     */
    public function getPersonasDentro(): array
    {
        $sql = "SELECT * FROM vista_acceso_externo 
                WHERE estado = 'DENTRO' 
                ORDER BY fecha_entrada DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Estadísticas de acceso externo
     */
    public function getEstadisticas(string $fechaDesde, string $fechaHasta): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_registros,
                    COUNT(CASE WHEN estado = 'DENTRO' THEN 1 END) as personas_dentro,
                    COUNT(CASE WHEN estado = 'SALIO' THEN 1 END) as personas_salieron,
                    AVG(tiempo_permanencia) as promedio_permanencia_minutos,
                    COUNT(DISTINCT documento) as visitantes_unicos
                FROM registros_acceso_externo
                WHERE DATE(fecha_entrada) BETWEEN :fecha_desde AND :fecha_hasta";

        return $this->db->fetchOne($sql, [
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta
        ]) ?? [];
    }
}
