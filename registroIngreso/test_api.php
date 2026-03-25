<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/models/conexion.php";

$conexion = new Conexion();
$db = $conexion->conectar();

if (!$db) {
    die(json_encode(['error' => 'Conexión fallida']));
}

// Simular la llamada al API
$_POST['action'] = 'obtener_aulas';

$response = ['success' => false, 'message' => 'Acción no especificada'];
$action = $_POST['action'] ?? '';

if ($action === 'obtener_aulas') {
    try {
        echo json_encode(['debug' => 'Iniciando obtener_aulas'], JSON_PRETTY_PRINT);
        echo "\n";
        
        $sql = "SELECT id_aula, nombre, descripcion, ubicacion, capacidad, total_llaves, disponibles FROM aulas ORDER BY nombre ASC";
        
        echo json_encode(['debug' => 'SQL: ' . $sql], JSON_PRETTY_PRINT);
        echo "\n";
        
        $result = $db->query($sql);
        
        if (!$result) {
            throw new Exception("Error en query: " . $db->error);
        }
        
        echo json_encode(['debug' => 'Query ejecutada, obteniendo datos...'], JSON_PRETTY_PRINT);
        echo "\n";
        
        $aulas = [];
        while ($row = $result->fetch_assoc()) {
            echo json_encode(['debug' => 'Aula encontrada: ' . $row['nombre']], JSON_PRETTY_PRINT);
            echo "\n";
            
            $id_aula = $row['id_aula'];
            
            // Obtener préstamos activos de esta aula
            $sql_prestamos = $db->prepare("SELECT id_prestamo, id_llave, usuario_retira, documento, hora_prestamo FROM prestamos_llaves WHERE id_aula = ? AND estado = 'Prestada' ORDER BY hora_prestamo DESC");
            $sql_prestamos->bind_param("i", $id_aula);
            $sql_prestamos->execute();
            $result_prestamos = $sql_prestamos->get_result();
            
            $prestamos = [];
            while ($prestamo = $result_prestamos->fetch_assoc()) {
                $prestamos[] = $prestamo;
            }
            $sql_prestamos->close();
            
            $row['prestamos_activos'] = $prestamos;
            $aulas[] = $row;
        }
        
        echo json_encode(['debug' => 'Total aulas: ' . count($aulas)], JSON_PRETTY_PRINT);
        echo "\n";
        
        $response = ['success' => true, 'data' => $aulas];
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$db->close();
?>
