<?php
/**
 * Modelo para Control de Llaves
 */

class KeysModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ========================================
    // GESTIÓN DE AULAS
    // ========================================

    /**
     * Obtener todas las aulas
     */
    public function getAllAulas(): array
    {
        $sql = "SELECT a.*,
                       (SELECT COUNT(*) FROM prestamos_llaves 
                        WHERE aula_id = a.id AND estado = 'PRESTADO') as llaves_prestadas
                FROM aulas a
                ORDER BY a.nombre ASC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Obtener aula por ID
     */
    public function getAulaById(int $id): ?array
    {
        $sql = "SELECT * FROM aulas WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Crear nueva aula
     */
    public function createAula(array $data): bool
    {
        $sql = "INSERT INTO aulas (nombre, capacidad, cantidad_llaves, observaciones)
                VALUES (:nombre, :capacidad, :cantidad_llaves, :observaciones)";
        
        try {
            $this->db->query($sql, $data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Actualizar aula
     */
    public function updateAula(int $id, array $data): bool
    {
        $sql = "UPDATE aulas 
                SET nombre = :nombre,
                    capacidad = :capacidad,
                    cantidad_llaves = :cantidad_llaves,
                    observaciones = :observaciones
                WHERE id = :id";
        
        $data['id'] = $id;
        try {
            $this->db->query($sql, $data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cambiar estado de aula
     */
    public function toggleAulaEstado(int $id): bool
    {
        $sql = "UPDATE aulas 
                SET estado = IF(estado = 'ACTIVO', 'INACTIVO', 'ACTIVO')
                WHERE id = :id";
        
        try {
            $this->db->query($sql, ['id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Eliminar aula
     */
    public function deleteAula(int $id): bool
    {
        $sql = "DELETE FROM aulas WHERE id = :id";
        try {
            $this->db->query($sql, ['id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // ========================================
    // GESTIÓN DE PRÉSTAMOS
    // ========================================

    /**
     * Obtener todas las aulas con información de disponibilidad y préstamos
     */
    public function getAulasConPrestamos(): array
    {
        $sql = "SELECT a.*,
                       (SELECT COUNT(*) FROM prestamos_llaves 
                        WHERE aula_id = a.id AND estado = 'PRESTADO') as llaves_prestadas,
                       (a.cantidad_llaves - (SELECT COUNT(*) FROM prestamos_llaves 
                        WHERE aula_id = a.id AND estado = 'PRESTADO')) as llaves_disponibles
                FROM aulas a
                ORDER BY a.nombre ASC";
        
        $aulas = $this->db->fetchAll($sql);
        
        // Obtener préstamos activos para cada aula
        foreach ($aulas as &$aula) {
            $aula['prestamos_activos'] = $this->getPrestamosActivosAula($aula['id']);
        }
        
        return $aulas;
    }

    /**
     * Obtener préstamos activos de un aula específica
     */
    public function getPrestamosActivosAula(int $aulaId): array
    {
        $sql = "SELECT pl.*,
                       p.nombres,
                       p.apellidos,
                       p.documento
                FROM prestamos_llaves pl
                INNER JOIN personas p ON pl.usuario_id = p.id
                WHERE pl.aula_id = :aula_id AND pl.estado = 'PRESTADO'
                ORDER BY pl.fecha_prestamo DESC";
        
        return $this->db->fetchAll($sql, ['aula_id' => $aulaId]);
    }

    /**
     * Obtener aulas disponibles para préstamo (sin cambios, por compatibilidad)
     */
    public function getAulasDisponibles(): array
    {
        $sql = "SELECT a.*,
                       (SELECT COUNT(*) FROM prestamos_llaves 
                        WHERE aula_id = a.id AND estado = 'PRESTADO') as llaves_prestadas,
                       (a.cantidad_llaves - (SELECT COUNT(*) FROM prestamos_llaves 
                        WHERE aula_id = a.id AND estado = 'PRESTADO')) as llaves_disponibles
                FROM aulas a
                WHERE a.estado = 'ACTIVO'
                HAVING llaves_disponibles > 0
                ORDER BY a.nombre ASC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Verificar si un usuario tiene llaves pendientes
     */
    public function tieneLlavesPendientes(int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*) as count
                FROM prestamos_llaves
                WHERE usuario_id = :usuario_id AND estado = 'PRESTADO'";
        
        $result = $this->db->fetchOne($sql, ['usuario_id' => $usuarioId]);
        return $result['count'] > 0;
    }

    /**
     * Obtener préstamo activo de un usuario
     */
    public function getPrestamoActivo(int $usuarioId): ?array
    {
        $sql = "SELECT pl.*,
                       a.nombre as aula_nombre,
                       p.nombres,
                       p.apellidos,
                       p.documento
                FROM prestamos_llaves pl
                INNER JOIN aulas a ON pl.aula_id = a.id
                INNER JOIN personas p ON pl.usuario_id = p.id
                WHERE pl.usuario_id = :usuario_id 
                  AND pl.estado = 'PRESTADO'
                ORDER BY pl.fecha_prestamo DESC
                LIMIT 1";
        
        return $this->db->fetchOne($sql, ['usuario_id' => $usuarioId]);
    }

    /**
     * Registrar préstamo de llave
     */
    public function prestarLlave(
        int $aulaId, 
        int $usuarioId, 
        string $nombreReceptor, 
        string $documentoReceptor,
        ?string $departamento = null,
        ?string $telefono = null,
        ?string $observaciones = null
    ): bool {
        $sql = "INSERT INTO prestamos_llaves (
                    aula_id, usuario_id, nombre_receptor, documento_receptor, 
                    departamento, telefono, observaciones_prestamo, estado
                ) VALUES (
                    :aula_id, :usuario_id, :nombre_receptor, :documento_receptor,
                    :departamento, :telefono, :observaciones, 'PRESTADO'
                )";
        
        try {
            $this->db->query($sql, [
                'aula_id' => $aulaId,
                'usuario_id' => $usuarioId,
                'nombre_receptor' => $nombreReceptor,
                'documento_receptor' => $documentoReceptor,
                'departamento' => $departamento,
                'telefono' => $telefono,
                'observaciones' => $observaciones
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Registrar devolución de llave
     */
    public function devolverLlave(int $prestamoId, ?string $observaciones = null): bool
    {
        $sql = "UPDATE prestamos_llaves 
                SET fecha_devolucion = NOW(),
                    estado = 'DEVUELTO',
                    observaciones_devolucion = :observaciones
                WHERE id = :id AND estado = 'PRESTADO'";
        
        try {
            $this->db->query($sql, [
                'id' => $prestamoId,
                'observaciones' => $observaciones
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtener historial de préstamos
     */
    public function getHistorialPrestamos(int $limit = 50): array
    {
        $sql = "SELECT pl.*,
                       a.nombre as aula_nombre,
                       pl.nombre_receptor,
                       pl.documento_receptor,
                       pl.departamento,
                       pl.telefono,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       cpt.nombre as tipo_persona
                FROM prestamos_llaves pl
                INNER JOIN aulas a ON pl.aula_id = a.id
                INNER JOIN personas p ON pl.usuario_id = p.id
                LEFT JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                ORDER BY pl.fecha_prestamo DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    /**
     * Obtener reporte de préstamos por rango de fechas
     */
    public function getReportePrestamos(string $fechaInicio, string $fechaFin, ?string $documento = null): array
    {
        $sql = "SELECT pl.*,
                       a.nombre as aula_nombre,
                       pl.nombre_receptor,
                       pl.documento_receptor,
                       pl.departamento,
                       pl.telefono,
                       p.documento,
                       CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo,
                       cpt.nombre as tipo_persona
                FROM prestamos_llaves pl
                INNER JOIN aulas a ON pl.aula_id = a.id
                INNER JOIN personas p ON pl.usuario_id = p.id
                LEFT JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                WHERE DATE(pl.fecha_prestamo) BETWEEN :fecha_inicio AND :fecha_fin";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        if ($documento !== null && $documento !== '') {
            $sql .= " AND p.documento = :documento";
            $params['documento'] = $documento;
        }

        $sql .= " ORDER BY pl.fecha_prestamo DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Obtener estadísticas de préstamos
     */
    public function getEstadisticas(): array
    {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM aulas WHERE estado = 'ACTIVO') as total_aulas,
                    (SELECT SUM(cantidad_llaves) FROM aulas WHERE estado = 'ACTIVO') as total_llaves,
                    (SELECT COUNT(*) FROM prestamos_llaves WHERE estado = 'PRESTADO') as llaves_prestadas,
                    (SELECT COUNT(*) FROM prestamos_llaves WHERE DATE(fecha_prestamo) = CURDATE()) as prestamos_hoy";
        
        return $this->db->fetchOne($sql) ?? [];
    }
}
