<?php

/**
 * Persistencia del módulo de ambientes y reservas.
 *
 * La disponibilidad nunca se guarda como una asignación permanente: se calcula
 * a partir de las reservas activas que se cruzan con un intervalo solicitado.
 */
class AmbientesModel
{
    private Database $db;
    private PDO $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->finalizeExpiredReservations();
    }

    public function getAmbientes(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $where = ['1 = 1'];
        $params = [];

        if (!empty($filters['search'])) {
            $where[] = '(a.codigo LIKE :search_code OR a.nombre LIKE :search_name OR a.ubicacion LIKE :search_location)';
            $search = '%' . trim($filters['search']) . '%';
            $params['search_code'] = $search;
            $params['search_name'] = $search;
            $params['search_location'] = $search;
        }
        if (!empty($filters['tipo'])) {
            $where[] = 'a.tipo = :tipo';
            $params['tipo'] = strtoupper($filters['tipo']);
        }
        if (!empty($filters['estado'])) {
            $where[] = 'a.estado = :estado';
            $params['estado'] = strtoupper($filters['estado']);
        }

        $whereSql = implode(' AND ', $where);
        $total = (int)($this->db->fetchOne(
            "SELECT COUNT(*) AS total FROM ambientes a WHERE {$whereSql}",
            $params
        )['total'] ?? 0);

        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = min($page, $lastPage);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT a.*,
                       (SELECT COUNT(*) FROM reservas r
                        WHERE r.ambiente_id = a.id AND r.estado = 'CREADA'
                          AND r.inicio < NOW() AND r.fin > NOW()) AS ocupado_ahora,
                       (SELECT COUNT(*) FROM reservas r
                        WHERE r.ambiente_id = a.id AND r.estado = 'CREADA'
                          AND r.inicio >= NOW()) AS reservas_futuras
                FROM ambientes a
                WHERE {$whereSql}
                ORDER BY a.nombre ASC
                LIMIT {$perPage} OFFSET {$offset}";

        return [
            'data' => $this->db->fetchAll($sql, $params),
            'pagination' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'total' => $total,
                'per_page' => $perPage,
            ],
        ];
    }

    public function getAmbienteById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT a.*,
                    (SELECT COUNT(*) FROM reservas r WHERE r.ambiente_id = a.id) AS total_reservas,
                    (SELECT COUNT(*) FROM reservas r WHERE r.ambiente_id = a.id AND r.estado = 'CREADA' AND r.inicio >= NOW()) AS reservas_futuras
             FROM ambientes a WHERE a.id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    public function getAmbientesActivos(): array
    {
        return $this->db->fetchAll(
            "SELECT id, codigo, nombre, tipo, ubicacion, capacidad
             FROM ambientes WHERE estado = 'DISPONIBLE' ORDER BY nombre"
        );
    }

    public function createAmbiente(array $data): int
    {
        $this->db->query(
            "INSERT INTO ambientes (codigo, nombre, tipo, ubicacion, capacidad, estado)
             VALUES (:codigo, :nombre, :tipo, :ubicacion, :capacidad, 'DISPONIBLE')",
            $data
        );
        return (int)$this->db->lastInsertId();
    }

    public function updateAmbiente(int $id, array $data): bool
    {
        $data['id'] = $id;
        return $this->db->query(
            "UPDATE ambientes SET codigo = :codigo, nombre = :nombre, tipo = :tipo,
                    ubicacion = :ubicacion, capacidad = :capacidad
             WHERE id = :id",
            $data
        )->rowCount() >= 0;
    }

    public function codigoExists(string $codigo, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) AS total FROM ambientes WHERE codigo = :codigo';
        $params = ['codigo' => $codigo];
        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $exceptId;
        }
        return (int)($this->db->fetchOne($sql, $params)['total'] ?? 0) > 0;
    }

    public function toggleAmbiente(int $id): bool
    {
        $ambiente = $this->getAmbienteById($id);
        if (!$ambiente) {
            return false;
        }
        $nuevoEstado = $ambiente['estado'] === 'INACTIVO' ? 'DISPONIBLE' : 'INACTIVO';
        return $this->db->query(
            'UPDATE ambientes SET estado = :estado WHERE id = :id',
            ['estado' => $nuevoEstado, 'id' => $id]
        )->rowCount() === 1;
    }

    public function getStats(): array
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS total,
                    COALESCE(SUM(estado = 'DISPONIBLE'), 0) AS activos,
                    COALESCE(SUM(estado = 'INACTIVO'), 0) AS inactivos,
                    COALESCE(SUM(estado = 'MANTENIMIENTO'), 0) AS mantenimiento,
                    (SELECT COUNT(*) FROM reservas WHERE estado = 'CREADA' AND DATE(inicio) = CURDATE()) AS reservas_hoy,
                    (SELECT COUNT(*) FROM reservas WHERE estado = 'CREADA' AND inicio < NOW() AND fin > NOW()) AS ocupados_ahora
             FROM ambientes"
        ) ?? [];

        return array_map('intval', array_merge([
            'total' => 0,
            'activos' => 0,
            'inactivos' => 0,
            'mantenimiento' => 0,
            'reservas_hoy' => 0,
            'ocupados_ahora' => 0,
        ], $row));
    }

    public function getPersonaIdForSystemUser(int $systemUserId): ?int
    {
        $row = $this->db->fetchOne(
            'SELECT persona_id FROM usuarios_sistema WHERE id = :id LIMIT 1',
            ['id' => $systemUserId]
        );
        return $row ? (int)$row['persona_id'] : null;
    }

    public function getReservas(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        [$whereSql, $params] = $this->buildReservaFilters($filters);
        $total = (int)($this->db->fetchOne(
            "SELECT COUNT(*) AS total FROM reservas r
             INNER JOIN ambientes a ON a.id = r.ambiente_id
             LEFT JOIN personas p ON p.id = r.solicitante_id
             WHERE {$whereSql}",
            $params
        )['total'] ?? 0);

        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = min($page, $lastPage);
        $offset = ($page - 1) * $perPage;

        $sql = $this->reservaSelect() . " WHERE {$whereSql}
                ORDER BY r.inicio DESC LIMIT {$perPage} OFFSET {$offset}";

        return [
            'data' => $this->db->fetchAll($sql, $params),
            'pagination' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'total' => $total,
                'per_page' => $perPage,
            ],
        ];
    }

    public function getReservaById(int $id): ?array
    {
        return $this->db->fetchOne(
            $this->reservaSelect() . ' WHERE r.id = :id LIMIT 1',
            ['id' => $id]
        );
    }

    public function createReserva(array $data): array
    {
        try {
            $this->connection->beginTransaction();
            $this->lockAmbiente((int)$data['ambiente_id']);
            if ($this->hasOverlap((int)$data['ambiente_id'], $data['inicio'], $data['fin'])) {
                $this->connection->rollBack();
                return ['success' => false, 'message' => 'El ambiente ya tiene una reserva que se superpone con ese horario.'];
            }

            $this->db->query(
                "INSERT INTO reservas (
                    ambiente_id, solicitante_id, responsable_nombre, responsable_documento,
                    creado_por, titulo, descripcion, inicio, fin, estado
                 ) VALUES (
                    :ambiente_id, :solicitante_id, :responsable_nombre, :responsable_documento,
                    :creado_por, :titulo, :descripcion, :inicio, :fin, 'CREADA'
                 )",
                $data
            );
            $id = (int)$this->db->lastInsertId();
            $this->connection->commit();
            return ['success' => true, 'id' => $id];
        } catch (Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            error_log('Error al crear reserva: ' . $e->getMessage());
            return ['success' => false, 'message' => 'No fue posible registrar la reserva.'];
        }
    }

    public function updateReserva(int $id, array $data): array
    {
        try {
            $this->connection->beginTransaction();
            $this->lockAmbiente((int)$data['ambiente_id']);
            if ($this->hasOverlap((int)$data['ambiente_id'], $data['inicio'], $data['fin'], $id)) {
                $this->connection->rollBack();
                return ['success' => false, 'message' => 'El ambiente ya tiene una reserva que se superpone con ese horario.'];
            }

            $data['id'] = $id;
            $this->db->query(
                "UPDATE reservas SET ambiente_id = :ambiente_id, solicitante_id = :solicitante_id,
                        responsable_nombre = :responsable_nombre,
                        responsable_documento = :responsable_documento,
                        titulo = :titulo, descripcion = :descripcion, inicio = :inicio, fin = :fin
                 WHERE id = :id AND estado = 'CREADA'",
                array_diff_key($data, ['creado_por' => true])
            );
            $this->connection->commit();
            return ['success' => true, 'id' => $id];
        } catch (Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            error_log('Error al actualizar reserva: ' . $e->getMessage());
            return ['success' => false, 'message' => 'No fue posible actualizar la reserva.'];
        }
    }

    public function cancelReserva(int $id): bool
    {
        return $this->db->query(
            "UPDATE reservas SET estado = 'CANCELADA' WHERE id = :id AND estado = 'CREADA'",
            ['id' => $id]
        )->rowCount() === 1;
    }

    public function hasOverlap(int $ambienteId, string $inicio, string $fin, ?int $exceptId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM reservas
                WHERE ambiente_id = :ambiente_id AND estado = 'CREADA'
                  AND inicio < :fin AND fin > :inicio";
        $params = ['ambiente_id' => $ambienteId, 'inicio' => $inicio, 'fin' => $fin];
        if ($exceptId !== null) {
            $sql .= ' AND id <> :except_id';
            $params['except_id'] = $exceptId;
        }
        return (int)($this->db->fetchOne($sql, $params)['total'] ?? 0) > 0;
    }

    public function getDisponibilidad(string $inicio, string $fin, ?int $capacidad = null, ?string $tipo = null): array
    {
        $where = ["a.estado = 'DISPONIBLE'"];
        $params = ['inicio' => $inicio, 'fin' => $fin];
        if ($capacidad !== null && $capacidad > 0) {
            $where[] = 'a.capacidad >= :capacidad';
            $params['capacidad'] = $capacidad;
        }
        if ($tipo) {
            $where[] = 'a.tipo = :tipo';
            $params['tipo'] = strtoupper($tipo);
        }

        return $this->db->fetchAll(
            "SELECT a.*,
                    EXISTS(SELECT 1 FROM reservas r
                           WHERE r.ambiente_id = a.id AND r.estado = 'CREADA'
                             AND r.inicio < :fin AND r.fin > :inicio) AS ocupado
             FROM ambientes a
             WHERE " . implode(' AND ', $where) . '
             ORDER BY ocupado ASC, a.capacidad ASC, a.nombre ASC',
            $params
        );
    }

    public function getCalendarEvents(string $inicio, string $fin, ?int $ambienteId = null): array
    {
        $sql = $this->reservaSelect() .
            " WHERE r.estado <> 'CANCELADA' AND r.inicio < :fin AND r.fin > :inicio";
        $params = ['inicio' => $inicio, 'fin' => $fin];
        if ($ambienteId) {
            $sql .= ' AND r.ambiente_id = :ambiente_id';
            $params['ambiente_id'] = $ambienteId;
        }
        $sql .= ' ORDER BY r.inicio';
        return $this->db->fetchAll($sql, $params);
    }

    public function getProximasReservas(int $limit = 6, ?int $createdBy = null): array
    {
        $sql = $this->reservaSelect() .
            " WHERE r.estado = 'CREADA' AND r.fin >= NOW()";
        $params = [];
        if ($createdBy) {
            $sql .= ' AND r.creado_por = :creado_por';
            $params['creado_por'] = $createdBy;
        }
        $limit = max(1, min(20, $limit));
        $sql .= " ORDER BY r.inicio ASC LIMIT {$limit}";
        return $this->db->fetchAll($sql, $params);
    }

    public function getReporte(array $filters): array
    {
        [$whereSql, $params] = $this->buildReservaFilters($filters);
        return $this->db->fetchAll(
            $this->reservaSelect() . " WHERE {$whereSql} ORDER BY r.inicio DESC",
            $params
        );
    }

    private function lockAmbiente(int $ambienteId): void
    {
        $stmt = $this->connection->prepare(
            "SELECT id FROM ambientes WHERE id = :id AND estado = 'DISPONIBLE' FOR UPDATE"
        );
        $stmt->execute(['id' => $ambienteId]);
        if (!$stmt->fetchColumn()) {
            throw new RuntimeException('El ambiente no existe o no está activo.');
        }
    }

    private function finalizeExpiredReservations(): void
    {
        $this->db->query(
            "UPDATE reservas SET estado = 'FINALIZADA'
             WHERE estado = 'CREADA' AND fin < NOW()"
        );
    }

    private function reservaSelect(): string
    {
        return "SELECT r.id, r.ambiente_id, r.solicitante_id, r.creado_por,
                       r.titulo, r.descripcion, r.inicio, r.fin, r.estado, r.created_at,
                       a.codigo AS ambiente_codigo, a.nombre AS ambiente_nombre,
                       a.tipo AS ambiente_tipo, a.ubicacion AS ambiente_ubicacion,
                       COALESCE(NULLIF(r.responsable_nombre, ''), CONCAT_WS(' ', p.nombres, p.apellidos)) AS responsable_nombre,
                       COALESCE(r.responsable_documento, p.documento) AS responsable_documento
                FROM reservas r
                INNER JOIN ambientes a ON a.id = r.ambiente_id
                LEFT JOIN personas p ON p.id = r.solicitante_id";
    }

    private function buildReservaFilters(array $filters): array
    {
        $where = ['1 = 1'];
        $params = [];
        if (!empty($filters['search'])) {
            $where[] = "(a.codigo LIKE :search_code OR a.nombre LIKE :search_environment OR r.titulo LIKE :search_title OR r.responsable_nombre LIKE :search_person OR r.responsable_documento LIKE :search_document)";
            $search = '%' . trim($filters['search']) . '%';
            $params['search_code'] = $search;
            $params['search_environment'] = $search;
            $params['search_title'] = $search;
            $params['search_person'] = $search;
            $params['search_document'] = $search;
        }
        if (!empty($filters['estado'])) {
            $where[] = 'r.estado = :estado';
            $params['estado'] = strtoupper($filters['estado']);
        }
        if (!empty($filters['ambiente_id'])) {
            $where[] = 'r.ambiente_id = :ambiente_id';
            $params['ambiente_id'] = (int)$filters['ambiente_id'];
        }
        if (!empty($filters['persona_id'])) {
            $where[] = 'r.solicitante_id = :persona_id';
            $params['persona_id'] = (int)$filters['persona_id'];
        }
        if (!empty($filters['creado_por'])) {
            $where[] = 'r.creado_por = :creado_por';
            $params['creado_por'] = (int)$filters['creado_por'];
        }
        if (!empty($filters['fecha_desde'])) {
            $where[] = 'DATE(r.inicio) >= :fecha_desde';
            $params['fecha_desde'] = $filters['fecha_desde'];
        }
        if (!empty($filters['fecha_hasta'])) {
            $where[] = 'DATE(r.inicio) <= :fecha_hasta';
            $params['fecha_hasta'] = $filters['fecha_hasta'];
        }
        return [implode(' AND ', $where), $params];
    }
}
