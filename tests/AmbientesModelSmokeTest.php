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
