<?php
/**
 * Modelo para Permisos de Salida
 */

class PermisosModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Buscar persona (aprendiz) por documento
     */
    public function buscarPersonaPorDocumento(string $documento): ?array
    {
        $sql = "SELECT p.id,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       p.estado,
                       CONCAT(p.nombres, ' ', COALESCE(p.apellidos, '')) as nombre_completo
                FROM personas p
                WHERE p.documento = :documento
                  AND p.deleted_at IS NULL
                LIMIT 1";

        return $this->db->fetchOne($sql, ['documento' => $documento]);
    }

    /**
     * Obtener datos del instructor por ID
     */
    public function getInstructorById(int $instructorId): ?array
    {
        $sql = "SELECT us.id, 
                       CONCAT(p.nombres, ' ', COALESCE(p.apellidos, '')) as nombre,
                       us.username,
                       us.rol_id,
                       us.estado
                FROM usuarios_sistema us
                INNER JOIN personas p ON us.persona_id = p.id
                WHERE us.id = :id
                  AND us.estado = 'ACTIVO'
                LIMIT 1";

        return $this->db->fetchOne($sql, ['id' => $instructorId]);
    }

    /**
     * Obtener todos los permisos con filtros
     */
    public function getPermisos(array $filters = []): array
    {
        $sql = "SELECT p.*
                FROM permisos_salida p
                WHERE 1=1";
        
        $params = [];
        
        // Filtro por fecha
        if (!empty($filters['fecha'])) {
            $sql .= " AND p.fecha_permiso = :fecha";
            $params['fecha'] = $filters['fecha'];
        }
        
        // Filtro por documento
        if (!empty($filters['documento'])) {
            $sql .= " AND p.documento_aprendiz LIKE :documento";
            $params['documento'] = '%' . $filters['documento'] . '%';
        }
        
        // Filtro por estado
        if (!empty($filters['estado'])) {
            $sql .= " AND p.estado = :estado";
            $params['estado'] = $filters['estado'];
        }
        
        // Filtro por instructor (para que solo vea sus permisos)
        if (!empty($filters['instructor_id'])) {
            $sql .= " AND p.instructor_id = :instructor_id";
            $params['instructor_id'] = $filters['instructor_id'];
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT 100";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Buscar permiso activo por documento
     */
    public function getPermisoActivoByDocumento(string $documento): ?array
    {
        $sql = "SELECT p.*
                FROM permisos_salida p
                WHERE p.documento_aprendiz = :documento
                AND p.estado = 'ACTIVO'
                AND p.fecha_permiso = CURDATE()
                ORDER BY p.created_at DESC
                LIMIT 1";
        
        return $this->db->fetchOne($sql, ['documento' => $documento]);
    }

    /**
     * Obtener permiso por ID
     */
    public function getPermisoById(int $id): ?array
    {
        $sql = "SELECT p.*
                FROM permisos_salida p
                WHERE p.id = :id";
        
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Crear nuevo permiso
     */
    public function createPermiso(array $data): bool
    {
        $sql = "INSERT INTO permisos_salida (
                    documento_aprendiz, 
                    nombre_aprendiz, 
                    fecha_permiso, 
                    hora_salida, 
                    hora_regreso, 
                    motivo, 
                    instructor_id, 
                    instructor_nombre,
                    observaciones
                ) VALUES (
                    :documento_aprendiz, 
                    :nombre_aprendiz, 
                    :fecha_permiso, 
                    :hora_salida, 
                    :hora_regreso, 
                    :motivo, 
                    :instructor_id, 
                    :instructor_nombre,
                    :observaciones
                )";
        
        try {
            $this->db->query($sql, $data);
            return true;
        } catch (Exception $e) {
            error_log("Error al crear permiso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar permiso como usado (cuando el vigilante valida la salida)
     */
    public function marcarPermisoUsado(int $permisoId, int $vigilanteId): bool
    {
        $sql = "UPDATE permisos_salida 
                SET estado = 'USADO',
                    usado_por = :vigilante_id,
                    fecha_uso = NOW()
                WHERE id = :permiso_id 
                AND estado = 'ACTIVO'";
        
        try {
            $stmt = $this->db->query($sql, [
                'permiso_id' => $permisoId,
                'vigilante_id' => $vigilanteId
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al marcar permiso usado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancelar permiso
     */
    public function cancelarPermiso(int $permisoId, string $motivo = ''): bool
    {
        $sql = "UPDATE permisos_salida 
                SET estado = 'CANCELADO',
                    observaciones = :observaciones
                WHERE id = :permiso_id";
        
        try {
            $this->db->query($sql, [
                'permiso_id' => $permisoId,
                'observaciones' => $motivo
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Marcar permisos vencidos (fecha pasada)
     */
    public function marcarPermisosVencidos(): int
    {
        $sql = "UPDATE permisos_salida 
                SET estado = 'VENCIDO'
                WHERE estado = 'ACTIVO'
                AND fecha_permiso < CURDATE()";
        
        try {
            $stmt = $this->db->query($sql);
            return $stmt->rowCount();
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Obtener estadísticas de permisos
     */
    public function getEstadisticas(string $fecha = null): array
    {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }
        
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'ACTIVO' THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN estado = 'USADO' THEN 1 ELSE 0 END) as usados,
                    SUM(CASE WHEN estado = 'VENCIDO' THEN 1 ELSE 0 END) as vencidos,
                    SUM(CASE WHEN estado = 'CANCELADO' THEN 1 ELSE 0 END) as cancelados
                FROM permisos_salida
                WHERE fecha_permiso = :fecha";
        
        $result = $this->db->fetchOne($sql, ['fecha' => $fecha]);
        
        return $result ?: [
            'total' => 0,
            'activos' => 0,
            'usados' => 0,
            'vencidos' => 0,
            'cancelados' => 0
        ];
    }

    /**
     * Verificar si ya existe un permiso activo para el documento en la fecha
     */
    public function existePermisoActivo(string $documento, string $fecha): bool
    {
        $sql = "SELECT COUNT(*) as count
                FROM permisos_salida
                WHERE documento_aprendiz = :documento
                AND fecha_permiso = :fecha
                AND estado IN ('ACTIVO', 'USADO')";
        
        $result = $this->db->fetchOne($sql, [
            'documento' => $documento,
            'fecha' => $fecha
        ]);
        
        return $result && $result['count'] > 0;
    }
}
