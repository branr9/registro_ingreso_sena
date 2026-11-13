<?php

/**
 * Prueba de integración ejecutable sin framework:
 * php tests/AmbientesModelSmokeTest.php
 */

session_id('ambientes_test_' . bin2hex(random_bytes(6)));
require dirname(__DIR__) . '/config/config.php';
session_write_close();
require APP_PATH . '/models/Database.php';
require APP_PATH . '/models/AmbientesModel.php';
require APP_PATH . '/controllers/AmbientesController.php';

$model = new AmbientesModel();
$connection = Database::getInstance()->getConnection();
$environmentId = 0;
$results = [];
$code = 'TEST-AMBIENTES-' . strtoupper(bin2hex(random_bytes(4)));

try {
    $environmentId = $model->createAmbiente([
        'codigo' => $code,
        'nombre' => 'Ambiente temporal de prueba',
        'tipo' => 'SALA',
        'ubicacion' => 'Pruebas automatizadas',
        'capacidad' => 20,
    ]);
    $results['creacion_ambiente'] = $environmentId > 0;

    $validationStart = date('Y-m-d', strtotime('+60 days'));
    $_SESSION['user_id'] = 1;
    $controller = new AmbientesController();
    $validateReserva = new ReflectionMethod($controller, 'validateReservaData');
    $validateReserva->setAccessible(true);
    [$validatedRecurring, $validationError] = $validateReserva->invoke($controller, [
        'ambiente_id' => $environmentId,
        'responsable_nombre' => 'Validación de recurrencia',
        'responsable_documento' => 'TEST-VALIDACION',
        'titulo' => 'Validación sin mutar fecha inicial',
        'descripcion' => '',
        'inicio' => $validationStart . 'T08:00',
        'fin' => $validationStart . 'T09:00',
        'recurrencia_semanal' => '1',
        'recurrencia_hasta' => date('Y-m-d', strtotime($validationStart . ' +14 days')),
    ]);
    $results['validacion_fecha_recurrente'] = $validationError === null
        && ($validatedRecurring['inicio'] ?? null) === $validationStart . ' 08:00:00';

    $date = date('Y-m-d', strtotime('+2 days'));
    $reservation = [
        'ambiente_id' => $environmentId,
        'solicitante_id' => null,
        'responsable_nombre' => 'Responsable externo de prueba',
        'responsable_documento' => 'EXT-12345',
        'creado_por' => null,
        'titulo' => 'Prueba automática de reserva',
        'descripcion' => null,
        'inicio' => $date . ' 08:00:00',
        'fin' => $date . ' 10:00:00',
    ];
    $created = $model->createReserva($reservation);
    $results['creacion_reserva'] = $created['success'] ?? false;

    $reservation['titulo'] = 'Prueba automática actualizada';
    $updated = !empty($created['id'])
        ? $model->updateReserva((int)$created['id'], $reservation)
        : ['success' => false];
    $results['actualizacion_reserva'] = $updated['success'] ?? false;

    $availability = $model->getDisponibilidad($reservation['inicio'], $reservation['fin']);
    $testedEnvironment = array_values(array_filter(
        $availability,
        static fn (array $environment): bool => (int)$environment['id'] === $environmentId
    ));
    $results['ocupacion_calculada'] = !empty($testedEnvironment)
        && (int)$testedEnvironment[0]['ocupado'] === 1;

    $reservation['inicio'] = $date . ' 09:00:00';
    $reservation['fin'] = $date . ' 11:00:00';
    $overlap = $model->createReserva($reservation);
    $results['rechazo_solapamiento'] = !($overlap['success'] ?? false);

    $results['cancelacion'] = !empty($created['id'])
        && $model->cancelReserva((int)$created['id']);

    $recurringStart = date('Y-m-d', strtotime('+4 days'));
    $recurring = $reservation;
    $recurring['titulo'] = 'Prueba automática recurrente';
    $recurring['inicio'] = $recurringStart . ' 08:00:00';
    $recurring['fin'] = $recurringStart . ' 10:00:00';
    $recurring['recurrencia_semanal'] = true;
    $recurring['recurrencia_hasta'] = date('Y-m-d', strtotime($recurringStart . ' +14 days'));
    $recurringCreated = $model->createReserva($recurring);
    $recurringCount = (int)$connection->query(
        "SELECT COUNT(*) FROM reservas WHERE ambiente_id = {$environmentId} AND titulo = 'Prueba automática recurrente' AND estado = 'CREADA'"
    )->fetchColumn();
    $results['creacion_reserva_recurrente'] = ($recurringCreated['success'] ?? false)
        && (int)($recurringCreated['count'] ?? 0) === 3
        && $recurringCount === 3
        && !empty($recurringCreated['id'])
        && $model->getReservaById((int)$recurringCreated['id']) !== null;

    $editableStart = date('Y-m-d', strtotime('+30 days'));
    $editableRecurring = $reservation;
    $editableRecurring['titulo'] = 'Prueba de recurrencia desde edición';
    $editableRecurring['inicio'] = $editableStart . ' 13:00:00';
    $editableRecurring['fin'] = $editableStart . ' 14:00:00';
    unset($editableRecurring['recurrencia_semanal'], $editableRecurring['recurrencia_hasta']);
    $editableCreated = $model->createReserva($editableRecurring);
    $editableRecurring['recurrencia_semanal'] = true;
    $editableRecurring['recurrencia_hasta'] = date('Y-m-d', strtotime($editableStart . ' +14 days'));
    $editableUpdated = !empty($editableCreated['id'])
        ? $model->updateReserva((int)$editableCreated['id'], $editableRecurring)
        : ['success' => false];
    $editableCount = (int)$connection->query(
        "SELECT COUNT(*) FROM reservas WHERE ambiente_id = {$environmentId} AND titulo = 'Prueba de recurrencia desde edición' AND estado = 'CREADA'"
    )->fetchColumn();
    $results['recurrencia_desde_edicion'] = ($editableUpdated['success'] ?? false)
        && (int)($editableUpdated['count'] ?? 0) === 3
        && $editableCount === 3;

    if (in_array(false, $results, true)) {
        throw new RuntimeException('Una o más verificaciones del módulo fallaron.');
    }

    echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
} finally {
    if ($environmentId > 0) {
        $statement = $connection->prepare(
            "DELETE FROM ambientes WHERE id = :id AND codigo = :codigo"
        );
        $statement->execute(['id' => $environmentId, 'codigo' => $code]);
    }
}
