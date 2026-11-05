<?php

/**
 * Controlador del módulo de administración y reserva temporal de ambientes.
 */
class AmbientesController
{
    private AmbientesModel $model;

    public function __construct()
    {
        $this->model = new AmbientesModel();
    }

    public function index(): void
    {
        $pageTitle = 'Ambientes';
        $stats = $this->model->getStats();
        $createdBy = Auth::hasRole('instructor') ? (int)$_SESSION['user_id'] : null;
        $proximasReservas = $this->model->getProximasReservas(6, $createdBy);
        require APP_PATH . '/views/ambientes/index.php';
    }

    public function gestion(): void
    {
        $pageTitle = 'Gestión de Ambientes';
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'tipo' => trim($_GET['tipo'] ?? ''),
            'estado' => trim($_GET['estado'] ?? ''),
        ];
        $result = $this->model->getAmbientes($filters, max(1, (int)($_GET['page'] ?? 1)));
        $ambientes = $result['data'];
        $pagination = $result['pagination'];
        require APP_PATH . '/views/ambientes/gestion.php';
    }

    public function create(): void
    {
        $pageTitle = 'Nuevo Ambiente';
        $ambiente = null;
        require APP_PATH . '/views/ambientes/form.php';
    }

    public function store(): void
    {
        $this->requireValidCsrf();
        [$data, $error] = $this->validateAmbienteData($_POST);
        if ($error) {
            setFlashMessage($error, 'error');
            redirect('/ambientes/crear');
        }
        if ($this->model->codigoExists($data['codigo'])) {
            setFlashMessage('Ya existe un ambiente con ese código.', 'error');
            redirect('/ambientes/crear');
        }

        try {
            $id = $this->model->createAmbiente($data);
            setFlashMessage('Ambiente creado exitosamente.', 'success');
            redirect('/ambientes/ver/' . $id);
        } catch (Throwable $e) {
            error_log('Error al crear ambiente: ' . $e->getMessage());
            setFlashMessage('No fue posible crear el ambiente.', 'error');
            redirect('/ambientes/crear');
        }
    }

    public function show(int $id): void
    {
        $ambiente = $this->model->getAmbienteById($id);
        if (!$ambiente) {
            setFlashMessage('Ambiente no encontrado.', 'error');
            redirect('/ambientes/gestion');
        }
        $pageTitle = 'Detalle del Ambiente';
        $reservasResult = $this->model->getReservas([
            'ambiente_id' => $id,
            'fecha_desde' => date('Y-m-d', strtotime('-30 days')),
            'fecha_hasta' => date('Y-m-d', strtotime('+90 days')),
        ], 1, 50);
        $reservas = $reservasResult['data'];
        require APP_PATH . '/views/ambientes/show.php';
    }

    public function edit(int $id): void
    {
        $ambiente = $this->model->getAmbienteById($id);
        if (!$ambiente) {
            setFlashMessage('Ambiente no encontrado.', 'error');
            redirect('/ambientes/gestion');
        }
        $pageTitle = 'Editar Ambiente';
        require APP_PATH . '/views/ambientes/form.php';
    }

    public function update(int $id): void
    {
        $this->requireValidCsrf();
        if (!$this->model->getAmbienteById($id)) {
            setFlashMessage('Ambiente no encontrado.', 'error');
            redirect('/ambientes/gestion');
        }
        [$data, $error] = $this->validateAmbienteData($_POST);
        if ($error) {
            setFlashMessage($error, 'error');
            redirect('/ambientes/editar/' . $id);
        }
        if ($this->model->codigoExists($data['codigo'], $id)) {
            setFlashMessage('Ya existe otro ambiente con ese código.', 'error');
            redirect('/ambientes/editar/' . $id);
        }

        try {
            $this->model->updateAmbiente($id, $data);
            setFlashMessage('Ambiente actualizado exitosamente.', 'success');
            redirect('/ambientes/ver/' . $id);
        } catch (Throwable $e) {
            error_log('Error al actualizar ambiente: ' . $e->getMessage());
            setFlashMessage('No fue posible actualizar el ambiente.', 'error');
            redirect('/ambientes/editar/' . $id);
        }
    }

    public function toggle(int $id): void
    {
        $this->requireValidCsrf();
        if ($this->model->toggleAmbiente($id)) {
            setFlashMessage('Estado del ambiente actualizado.', 'success');
        } else {
            setFlashMessage('No fue posible cambiar el estado del ambiente.', 'error');
        }
        redirect('/ambientes/gestion');
    }

    public function reservas(): void
    {
        $pageTitle = 'Reservas de Ambientes';
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'estado' => trim($_GET['estado'] ?? ''),
            'ambiente_id' => (int)($_GET['ambiente_id'] ?? 0),
            'fecha_desde' => $this->validDate($_GET['fecha_desde'] ?? '') ? $_GET['fecha_desde'] : '',
            'fecha_hasta' => $this->validDate($_GET['fecha_hasta'] ?? '') ? $_GET['fecha_hasta'] : '',
        ];
        if (Auth::hasRole('instructor')) {
            $filters['creado_por'] = (int)$_SESSION['user_id'];
        }
        $result = $this->model->getReservas($filters, max(1, (int)($_GET['page'] ?? 1)));
        $reservas = $result['data'];
        $pagination = $result['pagination'];
        $ambientes = $this->model->getAmbientesActivos();
        require APP_PATH . '/views/ambientes/reservas/index.php';
    }

    public function createReserva(): void
    {
        $pageTitle = 'Nueva Reserva';
        $reserva = null;
        $ambientes = $this->model->getAmbientesActivos();
        $preselectedAmbiente = max(0, (int)($_GET['ambiente_id'] ?? 0));
        $preselectedDate = $this->validDate($_GET['fecha'] ?? '') ? $_GET['fecha'] : date('Y-m-d');
        $preselectedStart = $this->validTime($_GET['hora_inicio'] ?? '') ? $_GET['hora_inicio'] : '08:00';
        $preselectedEnd = $this->validTime($_GET['hora_fin'] ?? '') ? $_GET['hora_fin'] : '10:00';
        require APP_PATH . '/views/ambientes/reservas/form.php';
    }

    public function storeReserva(): void
    {
        $this->requireValidCsrf();
        [$data, $error] = $this->validateReservaData($_POST);
        if ($error) {
            setFlashMessage($error, 'error');
            redirect('/ambientes/reservas/crear');
        }

        $result = $this->model->createReserva($data);
        if (!$result['success']) {
            setFlashMessage($result['message'], 'error');
            redirect('/ambientes/reservas/crear');
        }
        setFlashMessage('Reserva registrada exitosamente.', 'success');
        redirect('/ambientes/reservas/ver/' . $result['id']);
    }

    public function showReserva(int $id): void
    {
        $reserva = $this->model->getReservaById($id);
        if (!$reserva || !$this->canAccessReserva($reserva)) {
            setFlashMessage('Reserva no encontrada o sin permisos para consultarla.', 'error');
            redirect('/ambientes/reservas');
        }
        $pageTitle = 'Detalle de Reserva';
        require APP_PATH . '/views/ambientes/reservas/show.php';
    }

    public function editReserva(int $id): void
    {
        $reserva = $this->model->getReservaById($id);
        if (!$reserva || !$this->canManageReserva($reserva)) {
            setFlashMessage('La reserva no puede ser editada.', 'error');
            redirect('/ambientes/reservas');
        }
        $pageTitle = 'Editar Reserva';
        $ambientes = $this->model->getAmbientesActivos();
        $preselectedAmbiente = 0;
        $preselectedDate = date('Y-m-d', strtotime($reserva['inicio']));
        $preselectedStart = date('H:i', strtotime($reserva['inicio']));
        $preselectedEnd = date('H:i', strtotime($reserva['fin']));
        require APP_PATH . '/views/ambientes/reservas/form.php';
    }

    public function updateReserva(int $id): void
    {
        $this->requireValidCsrf();
        $reserva = $this->model->getReservaById($id);
        if (!$reserva || !$this->canManageReserva($reserva)) {
            setFlashMessage('La reserva no puede ser editada.', 'error');
            redirect('/ambientes/reservas');
        }
        [$data, $error] = $this->validateReservaData($_POST);
        if ($error) {
            setFlashMessage($error, 'error');
            redirect('/ambientes/reservas/editar/' . $id);
        }
        $result = $this->model->updateReserva($id, $data);
        if (!$result['success']) {
            setFlashMessage($result['message'], 'error');
            redirect('/ambientes/reservas/editar/' . $id);
        }
        setFlashMessage('Reserva actualizada exitosamente.', 'success');
        redirect('/ambientes/reservas/ver/' . $id);
    }

    public function cancelReserva(int $id): void
    {
        $this->requireValidCsrf();
        $reserva = $this->model->getReservaById($id);
        if (!$reserva || !$this->canManageReserva($reserva)) {
            setFlashMessage('La reserva no puede ser cancelada.', 'error');
            redirect('/ambientes/reservas');
        }
        if ($this->model->cancelReserva($id)) {
            setFlashMessage('Reserva cancelada exitosamente.', 'success');
        } else {
            setFlashMessage('La reserva ya no se encontraba activa.', 'error');
        }
        redirect('/ambientes/reservas');
    }

    public function calendario(): void
    {
        $pageTitle = 'Calendario de Reservas';
        $ambientes = $this->model->getAmbientesActivos();
        require APP_PATH . '/views/ambientes/calendario.php';
    }

    public function calendarEvents(): void
    {
        $inicio = $this->parseIsoDate($_GET['inicio'] ?? '', false);
        $fin = $this->parseIsoDate($_GET['fin'] ?? '', true);
        if (!$inicio || !$fin || $fin <= $inicio) {
            jsonResponse(['success' => false, 'message' => 'Rango de fechas inválido.'], 422);
        }
        $events = $this->model->getCalendarEvents(
            $inicio->format('Y-m-d H:i:s'),
            $fin->format('Y-m-d H:i:s'),
            max(0, (int)($_GET['ambiente_id'] ?? 0)) ?: null
        );
        $currentSystemUserId = Auth::hasRole('instructor') ? (int)$_SESSION['user_id'] : null;
        $currentPersonaId = $currentSystemUserId !== null ? $this->currentPersonaId() : null;
        jsonResponse(['success' => true, 'events' => array_map(static function (array $event) use ($currentSystemUserId, $currentPersonaId): array {
            return [
                'id' => (int)$event['id'],
                'title' => $event['titulo'] ?: $event['ambiente_nombre'],
                'ambiente' => $event['ambiente_codigo'] . ' - ' . $event['ambiente_nombre'],
                'responsable' => $event['responsable_nombre'],
                'start' => date('c', strtotime($event['inicio'])),
                'end' => date('c', strtotime($event['fin'])),
                'url' => $currentSystemUserId === null
                    || (int)$event['creado_por'] === $currentSystemUserId
                    || (!empty($event['solicitante_id']) && (int)$event['solicitante_id'] === $currentPersonaId)
                    ? baseUrl('/ambientes/reservas/ver/' . $event['id'])
                    : null,
            ];
        }, $events)]);
    }

    public function disponibilidad(): void
    {
        $pageTitle = 'Disponibilidad de Ambientes';
        $fecha = $this->validDate($_GET['fecha'] ?? '') ? $_GET['fecha'] : date('Y-m-d');
        $horaInicio = $this->validTime($_GET['hora_inicio'] ?? '') ? $_GET['hora_inicio'] : '08:00';
        $horaFin = $this->validTime($_GET['hora_fin'] ?? '') ? $_GET['hora_fin'] : '10:00';
        $capacidad = max(0, (int)($_GET['capacidad'] ?? 0));
        $tipo = in_array(strtoupper($_GET['tipo'] ?? ''), ['SALA', 'LABORATORIO', 'AUDITORIO'], true)
            ? strtoupper($_GET['tipo']) : '';
        $inicio = $fecha . ' ' . $horaInicio . ':00';
        $fin = $fecha . ' ' . $horaFin . ':00';
        $errorHorario = strtotime($fin) <= strtotime($inicio);
        $ambientes = $errorHorario ? [] : $this->model->getDisponibilidad($inicio, $fin, $capacidad ?: null, $tipo ?: null);
        require APP_PATH . '/views/ambientes/disponibilidad.php';
    }

    public function exportReporte(): void
    {
        $filters = [
            'estado' => trim($_GET['estado'] ?? ''),
            'ambiente_id' => (int)($_GET['ambiente_id'] ?? 0),
            'fecha_desde' => $this->validDate($_GET['fecha_desde'] ?? '') ? $_GET['fecha_desde'] : '',
            'fecha_hasta' => $this->validDate($_GET['fecha_hasta'] ?? '') ? $_GET['fecha_hasta'] : '',
        ];
        if (Auth::hasRole('instructor')) {
            $filters['creado_por'] = (int)$_SESSION['user_id'];
        }
        $rows = $this->model->getReporte($filters);
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="reporte_reservas_' . date('Ymd_His') . '.csv"');
        echo "\xEF\xBB\xBF";
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Código', 'Ambiente', 'Responsable', 'Documento responsable', 'Título', 'Inicio', 'Fin', 'Estado'], ';');
        foreach ($rows as $row) {
            fputcsv($out, [
                $row['ambiente_codigo'], $row['ambiente_nombre'], $row['responsable_nombre'],
                $row['responsable_documento'],
                $row['titulo'], $row['inicio'], $row['fin'], $row['estado'],
            ], ';');
        }
        fclose($out);
        exit;
    }

    private function validateAmbienteData(array $input): array
    {
        $codigo = strtoupper(trim($input['codigo'] ?? ''));
        $nombre = trim($input['nombre'] ?? '');
        $tipo = strtoupper(trim($input['tipo'] ?? ''));
        $ubicacion = trim($input['ubicacion'] ?? '');
        $capacidad = (int)($input['capacidad'] ?? 0);

        if (!preg_match('/^[A-Z0-9._-]{2,40}$/', $codigo)) {
            return [[], 'El código debe tener entre 2 y 40 caracteres y solo usar letras, números, punto, guion o guion bajo.'];
        }
        if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 120) {
            return [[], 'El nombre debe tener entre 3 y 120 caracteres.'];
        }
        if (!in_array($tipo, ['SALA', 'LABORATORIO', 'AUDITORIO'], true)) {
            return [[], 'Seleccione un tipo de ambiente válido.'];
        }
        if (mb_strlen($ubicacion) > 160 || $capacidad < 1 || $capacidad > 1000) {
            return [[], 'Revise la ubicación y la capacidad del ambiente.'];
        }
        return [[
            'codigo' => $codigo,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'ubicacion' => $ubicacion !== '' ? $ubicacion : null,
            'capacidad' => $capacidad,
        ], null];
    }

    private function validateReservaData(array $input): array
    {
        $ambienteId = (int)($input['ambiente_id'] ?? 0);
        $responsableNombre = trim($input['responsable_nombre'] ?? '');
        $responsableDocumento = strtoupper(trim($input['responsable_documento'] ?? ''));
        $titulo = trim($input['titulo'] ?? '');
        $descripcion = trim($input['descripcion'] ?? '');
        $inicio = $this->parseLocalDateTime($input['inicio'] ?? '');
        $fin = $this->parseLocalDateTime($input['fin'] ?? '');

        if ($ambienteId < 1 || !$this->model->getAmbienteById($ambienteId)) {
            return [[], 'Seleccione un ambiente válido.'];
        }
        if (mb_strlen($responsableNombre) < 3 || mb_strlen($responsableNombre) > 150) {
            return [[], 'Ingrese el nombre de la persona responsable del ambiente.'];
        }
        if (mb_strlen($responsableDocumento) > 30) {
            return [[], 'El documento del responsable no puede superar 30 caracteres.'];
        }
        if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 160 || mb_strlen($descripcion) > 255) {
            return [[], 'Revise el título y la descripción de la reserva.'];
        }
        if (!$inicio || !$fin || $fin <= $inicio) {
            return [[], 'La fecha y hora final deben ser posteriores al inicio.'];
        }
        if ($inicio->format('Y-m-d') !== $fin->format('Y-m-d')) {
            return [[], 'La reserva debe comenzar y terminar el mismo día.'];
        }
        if ($inicio < new DateTime('today')) {
            return [[], 'No se pueden registrar reservas en fechas pasadas.'];
        }

        return [[
            'ambiente_id' => $ambienteId,
            'solicitante_id' => null,
            'responsable_nombre' => $responsableNombre,
            'responsable_documento' => $responsableDocumento !== '' ? $responsableDocumento : null,
            'creado_por' => (int)$_SESSION['user_id'],
            'titulo' => $titulo,
            'descripcion' => $descripcion !== '' ? $descripcion : null,
            'inicio' => $inicio->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s'),
        ], null];
    }

    private function currentPersonaId(): int
    {
        $personaId = $this->model->getPersonaIdForSystemUser((int)($_SESSION['user_id'] ?? 0));
        if (!$personaId) {
            throw new RuntimeException('No se encontró la persona asociada al usuario actual.');
        }
        return $personaId;
    }

    private function canAccessReserva(array $reserva): bool
    {
        if (Auth::hasRole('admin') || (int)($reserva['creado_por'] ?? 0) === (int)$_SESSION['user_id']) {
            return true;
        }

        return !empty($reserva['solicitante_id'])
            && (int)$reserva['solicitante_id'] === $this->currentPersonaId();
    }

    private function canManageReserva(array $reserva): bool
    {
        return $reserva['estado'] === 'CREADA'
            && strtotime($reserva['fin']) > time()
            && $this->canAccessReserva($reserva);
    }

    private function requireValidCsrf(): void
    {
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('La sesión del formulario expiró. Intente nuevamente.', 'error');
            redirect('/ambientes');
        }
    }

    private function parseLocalDateTime(string $value): ?DateTime
    {
        $date = DateTime::createFromFormat('!Y-m-d\TH:i', $value);
        return $date && $date->format('Y-m-d\TH:i') === $value ? $date : null;
    }

    private function parseIsoDate(string $value, bool $endOfDay): ?DateTime
    {
        if (!$this->validDate($value)) {
            return null;
        }
        return new DateTime($value . ($endOfDay ? ' 23:59:59' : ' 00:00:00'));
    }

    private function validDate(string $value): bool
    {
        $date = DateTime::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value;
    }

    private function validTime(string $value): bool
    {
        return (bool)preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $value);
    }
}
