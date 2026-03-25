<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/models/conexion.php";

$conexion = new Conexion();
$db = $conexion->conectar();

// Verificar conexión
if (!$db) {
    die(json_encode(['error' => 'Conexión fallida']));
}

// 1. Verificar que la BD existe y tiene tablas
$resultado = $db->query("SHOW TABLES");
$tablas = [];
while ($row = $resultado->fetch_row()) {
    $tablas[] = $row[0];
}

// 2. Contar aulas
$result_aulas = $db->query("SELECT COUNT(*) as total FROM aulas");
$count_aulas = $result_aulas->fetch_assoc()['total'];

// 3. Obtener todas las aulas
$result_datos = $db->query("SELECT * FROM aulas");
$aulas = [];
while ($row = $result_datos->fetch_assoc()) {
    $aulas[] = $row;
}

echo json_encode([
    'conexion' => 'OK',
    'base_datos' => 'nexus',
    'tablas_detectadas' => $tablas,
    'total_aulas' => $count_aulas,
    'aulas' => $aulas
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$db->close();
?>
