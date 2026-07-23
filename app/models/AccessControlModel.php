<?php

/**
 * Modelo para Control de Ingreso con Código de Barras
 * 
 * Maneja validación de códigos de barras, estados de personas y
 * registro de marcaciones ENTRADA/SALIDA.
 */
class AccessControlModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Buscar persona por número de documento (código de barras)
     * 
     * @param string $documento Número de documento
     * @return array|null Datos de persona si existe
     */
    public function findByDocumento(string $documento): ?array
    {
        $sql = "SELECT p.id as persona_id,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       p.estado as persona_estado,
                       p.tipo_persona_id,
                       cpt.codigo as tipo_persona,
                       cpt.nombre as tipo_persona_nombre
                FROM personas p
                INNER JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                WHERE p.documento = :documento
                  AND p.deleted_at IS NULL
                LIMIT 1";

        return $this->db->fetchOne($sql, ['documento' => $documento]);
    }

    /**
     * Validar si la persona puede acceder
     * 
     * Reglas:
     * - ACTIVO: acceso permitido
     * - INACTIVO: acceso denegado
     * 
     * @param array $persona Datos de persona
     * @return array ['allowed' => bool, 'reason' => string, 'tipo_evento' => string]
     */
    public function validateAccess(array $persona): array
    {
        // Validar estado
        if (strtoupper($persona['persona_estado']) !== 'ACTIVO') {
            return [
                'allowed' => false,
                'reason' => 'Persona inactiva',
                'tipo_evento' => null
            ];
        }

        // Determinar si es ENTRADA o SALIDA
        $lastEntry = $this->getLastEntryToday($persona['persona_id']);
        
        if ($lastEntry === null) {
            // Primera marcación del día = ENTRADA
            $tipoEvento = 'ENTRADA';
        } else {
            // Alternar según última marcación
            $tipoEvento = ($lastEntry['tipo_evento'] === 'ENTRADA') ? 'SALIDA' : 'ENTRADA';
        }

        return [
            'allowed' => true,
            'reason' => 'Acceso autorizado',
            'tipo_evento' => $tipoEvento
        ];
    }

    /**
     * Obtener última marcación exitosa del día para una persona
     * 
     * @param int $personaId ID de persona
     * @return array|null Última marcación o null si no hay
     */
    public function getLastEntryToday(int $personaId): ?array
    {
        $sql = "SELECT tipo_evento, fecha_hora
                FROM marcaciones
                WHERE persona_id = :persona_id
                  AND DATE(fecha_hora) = CURDATE()
                  AND exitoso = 1
                ORDER BY fecha_hora DESC
                LIMIT 1";

        return $this->db->fetchOne($sql, ['persona_id' => $personaId]);
    }

    /**
     * Registrar marcación de acceso (exitosa o fallida)
     * 
     * @param array $data Datos de la marcación
     * @return bool Éxito de la operación
     */
    public function recordAccess(array $data): bool
    {
        $sql = "INSERT INTO marcaciones (
                    persona_id,
                    dispositivo_id,
                    tipo_evento,
                    metodo,
                    motivo_id,
                    documento_capturado,
                    nombre_capturado,
                    exitoso,
                    mensaje,
                    registrado_por,
                    fecha_hora
                ) VALUES (
                    :persona_id,
                    :dispositivo_id,
                    :tipo_evento,
                    :metodo,
                    :motivo_id,
                    :documento_capturado,
                    :nombre_capturado,
                    :exitoso,
                    :mensaje,
                    :registrado_por,
                    NOW()
                )";

        $params = [
            'persona_id' => $data['persona_id'] ?? null,
            'dispositivo_id' => $data['dispositivo_id'] ?? null,
            'tipo_evento' => $data['tipo_evento'] ?? null,
            'metodo' => $data['metodo'] ?? 'BARCODE',
            'motivo_id' => $data['motivo_id'] ?? null,
            'documento_capturado' => $data['documento'] ?? null,
            'nombre_capturado' => $data['nombre'] ?? null,
            'exitoso' => $data['exitoso'] ? 1 : 0,
            'mensaje' => $data['mensaje'] ?? null,
            'registrado_por' => $_SESSION['user_id'] ?? null
        ];

        $stmt = $this->db->query($sql, $params);
        return $stmt !== false;
    }

    /**
     * Obtener lista de personas activas (para simulador)
     * 
     * @return array Lista de personas
     */
    public function getPersonasActivas(): array
    {
        $sql = "SELECT p.id,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       p.estado,
                       cpt.nombre as tipo_persona
                FROM personas p
                INNER JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                WHERE p.deleted_at IS NULL
                ORDER BY p.nombres ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Obtener marcaciones recientes (para mostrar en kiosko)
     * 
     * @param int $limit Cantidad de registros
     * @return array Lista de marcaciones
     */
    public function getRecentAccess(int $limit = 10): array
    {
        $sql = "SELECT m.id,
                       m.tipo_evento,
                       m.exitoso,
                       m.mensaje,
                       m.fecha_hora,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       cpt.nombre as tipo_persona
                FROM marcaciones m
                LEFT JOIN personas p ON m.persona_id = p.id
                LEFT JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                WHERE m.metodo = 'BARCODE'
                ORDER BY m.fecha_hora DESC
                LIMIT :limit";

        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    /**
     * Obtener estadísticas del día actual
     * 
     * @return array Contadores de accesos
     */
    public function getTodayStats(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN exitoso = 1 AND tipo_evento = 'ENTRADA' THEN 1 ELSE 0 END) as entradas,
                    SUM(CASE WHEN exitoso = 1 AND tipo_evento = 'SALIDA' THEN 1 ELSE 0 END) as salidas,
                    SUM(CASE WHEN exitoso = 0 THEN 1 ELSE 0 END) as denegados
                FROM marcaciones
                WHERE DATE(fecha_hora) = CURDATE()
                  AND metodo = 'BARCODE'";

        return $this->db->fetchOne($sql) ?? [
            'total' => 0,
            'entradas' => 0,
            'salidas' => 0,
            'denegados' => 0
        ];
    }

    /**
     * Obtener reporte de accesos por rango de fechas
     * 
     * @param string $fechaInicio Fecha inicial (Y-m-d)
     * @param string $fechaFin Fecha final (Y-m-d)
     * @param string|null $documento Filtro opcional por documento
     * @return array Lista de marcaciones
     */
    public function getAccessReport(string $fechaInicio, string $fechaFin, ?string $documento = null): array
    {
        $sql = "SELECT m.id,
                       m.tipo_evento as tipo_acceso,
                       m.metodo,
                       m.exitoso,
                       m.mensaje,
                       m.fecha_hora,
                       p.documento,
                       CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo,
                       cpt.nombre as tipo_persona
                FROM marcaciones m
                LEFT JOIN personas p ON m.persona_id = p.id
                LEFT JOIN cat_persona_tipo cpt ON p.tipo_persona_id = cpt.id
                WHERE DATE(m.fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin";

        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        // Agregar filtro por documento si se proporciona
        if ($documento !== null && $documento !== '') {
            $sql .= " AND p.documento = :documento";
            $params['documento'] = $documento;
        }

        $sql .= " ORDER BY m.fecha_hora DESC";

        return $this->db->fetchAll($sql, $params);
    }
}
