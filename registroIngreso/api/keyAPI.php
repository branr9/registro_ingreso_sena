<?php
header('Content-Type: application/json');
require_once __DIR__ . "/../models/conexion.php";

$conexion = new Conexion();
$db = $conexion->conectar();

$response = ['success' => false, 'message' => 'Acción no especificada'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        // ========== CREAR AULA ==========
        case 'crear_aula':
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $ubicacion = trim($_POST['ubicacion'] ?? '');
            $capacidad = intval($_POST['capacidad'] ?? 30);
            $total_llaves = intval($_POST['total_llaves'] ?? 0);
            $responsable = trim($_POST['responsable'] ?? 'No asignado');

            if (empty($nombre) || $total_llaves <= 0) {
                $response = ['success' => false, 'message' => 'Nombre y cantidad de llaves son obligatorios'];
            } else {
                try {
                    // Insertar aula con disponibles = total_llaves y activa = 1 explícitamente
                    $sql = $db->prepare("INSERT INTO aulas (nombre, descripcion, ubicacion, capacidad, total_llaves, disponibles, responsable, activa) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
                    
                    if (!$sql) {
                        throw new Exception("Error en prepare: " . $db->error);
                    }
                    
                    $disponibles = $total_llaves;
                    $sql->bind_param("sssiiis", $nombre, $descripcion, $ubicacion, $capacidad, $total_llaves, $disponibles, $responsable);
                    
                    if (!$sql->execute()) {
                        throw new Exception("Error al insertar aula: " . $sql->error);
                    }
                    
                    $id_aula = $db->insert_id;
                    
                    // Crear las llaves
                    for ($i = 1; $i <= $total_llaves; $i++) {
                        $numero = "LLV-" . str_pad($id_aula, 3, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $sql_llave = $db->prepare("INSERT INTO llaves (id_aula, numero_llave, disponible) VALUES (?, ?, 1)");
                        
                        if (!$sql_llave) {
                            throw new Exception("Error en prepare llave: " . $db->error);
                        }
                        
                        $sql_llave->bind_param("is", $id_aula, $numero);
                        
                        if (!$sql_llave->execute()) {
                            throw new Exception("Error al insertar llave: " . $sql_llave->error);
                        }
                        $sql_llave->close();
                    }
                    
                    $response = ['success' => true, 'message' => 'Aula registrada exitosamente', 'id_aula' => $id_aula];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
            }
            break;

        // ========== REGISTRAR PRÉSTAMO ==========
        case 'registrar_prestamo':
            $id_aula = intval($_POST['id_aula'] ?? 0);
            $id_llave = intval($_POST['id_llave'] ?? 0);
            $usuario = trim($_POST['usuario'] ?? 'No registrado');
            $documento = trim($_POST['documento'] ?? '');

            if ($id_aula <= 0 || $id_llave <= 0) {
                $response = ['success' => false, 'message' => 'Aula y llave son obligatorias'];
            } else {
                try {
                    // Verificar disponibilidad de llave
                    $check = $db->prepare("SELECT disponible FROM llaves WHERE id_llave = ? AND id_aula = ?");
                    $check->bind_param("ii", $id_llave, $id_aula);
                    $check->execute();
                    $result = $check->get_result();
                    $key_data = $result->fetch_assoc();
                    $check->close();
                    
                    if (!$key_data || !$key_data['disponible']) {
                        throw new Exception("La llave no está disponible");
                    }
                    
                    // Insertar préstamo
                    $sql = $db->prepare("INSERT INTO prestamos_llaves (id_llave, id_usuario, id_aula, usuario_retira, documento, estado, fecha_prestamo, hora_prestamo) VALUES (?, ?, ?, ?, ?, 'Prestada', CURDATE(), CURTIME())");
                    
                    if (!$sql) {
                        throw new Exception("Error en prepare: " . $db->error);
                    }
                    
                    $id_usuario = 1; // Usuario por defecto, ajusta según tu lógica
                    $sql->bind_param("iisss", $id_llave, $id_usuario, $id_aula, $usuario, $documento);
                    
                    if (!$sql->execute()) {
                        throw new Exception("Error al registrar préstamo: " . $sql->error);
                    }
                    $sql->close();
                    
                    // Actualizar llave como no disponible
                    $update = $db->prepare("UPDATE llaves SET disponible = 0 WHERE id_llave = ?");
                    $update->bind_param("i", $id_llave);
                    $update->execute();
                    $update->close();
                    
                    // Actualizar contador de aula
                    $update_aula = $db->prepare("UPDATE aulas SET disponibles = disponibles - 1 WHERE id_aula = ?");
                    $update_aula->bind_param("i", $id_aula);
                    $update_aula->execute();
                    $update_aula->close();
                    
                    $response = ['success' => true, 'message' => 'Préstamo registrado exitosamente'];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
            }
            break;

        // ========== DEVOLVER LLAVE ==========
        case 'devolver_llave':
            $id_prestamo = intval($_POST['id_prestamo'] ?? 0);

            if ($id_prestamo <= 0) {
                $response = ['success' => false, 'message' => 'Préstamo no válido'];
            } else {
                try {
                    // Obtener datos del préstamo
                    $get_prestamo = $db->prepare("SELECT id_llave, id_aula FROM prestamos_llaves WHERE id_prestamo = ? AND estado = 'Prestada'");
                    $get_prestamo->bind_param("i", $id_prestamo);
                    $get_prestamo->execute();
                    $prestamo = $get_prestamo->get_result()->fetch_assoc();
                    $get_prestamo->close();
                    
                    if (!$prestamo) {
                        throw new Exception("Préstamo no encontrado o ya fue devuelto");
                    }
                    
                    // Actualizar estado del préstamo
                    // ✅ Después
                    // ✅ Correcto
                    $sql = $db->prepare("UPDATE prestamos_llaves SET estado = 'Devuelto', fecha_devolucion = CURDATE(), hora_devolucion = CURTIME() WHERE id_prestamo = ?");                    $sql->bind_param("i", $id_prestamo);
                    
                    if (!$sql->execute()) {
                        throw new Exception("Error al devolver: " . $sql->error);
                    }
                    $sql->close();
                    
                    // Actualizar llave como disponible
                    $update = $db->prepare("UPDATE llaves SET disponible = 1 WHERE id_llave = ?");
                    $update->bind_param("i", $prestamo['id_llave']);
                    $update->execute();
                    $update->close();
                    
                    // Actualizar contador de aula
                    $update_aula = $db->prepare("UPDATE aulas SET disponibles = disponibles + 1 WHERE id_aula = ?");
                    $update_aula->bind_param("i", $prestamo['id_aula']);
                    $update_aula->execute();
                    $update_aula->close();
                    
                    $response = ['success' => true, 'message' => 'Devolución registrada exitosamente'];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
            }
            break;

        // ========== OBTENER AULAS CON PRÉSTAMOS ACTIVOS ==========
        case 'obtener_aulas':
            try {
                $sql = "SELECT id_aula, nombre, descripcion, ubicacion, capacidad, total_llaves, disponibles FROM aulas ORDER BY nombre ASC";
                $result = $db->query($sql);
                
                if (!$result) {
                    throw new Exception("Error en query: " . $db->error);
                }
                
                $aulas = [];
                while ($row = $result->fetch_assoc()) {
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
                
                $response = ['success' => true, 'data' => $aulas];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
            break;

        // ========== OBTENER LLAVES DE UN AULA ==========
        case 'obtener_llaves_aula':
            $id_aula = intval($_POST['id_aula'] ?? 0);
            
            if ($id_aula <= 0) {
                $response = ['success' => false, 'message' => 'Aula no válida'];
            } else {
                try {
                    $sql = $db->prepare("SELECT id_llave, numero_llave, disponible FROM llaves WHERE id_aula = ? ORDER BY numero_llave ASC");
                    $sql->bind_param("i", $id_aula);
                    $sql->execute();
                    $result = $sql->get_result();
                    
                    $llaves = [];
                    while ($row = $result->fetch_assoc()) {
                        $llaves[] = $row;
                    }
                    $sql->close();
                    
                    $response = ['success' => true, 'data' => $llaves];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
            }
            break;

        // ========== OBTENER PRÉSTAMOS ACTIVOS ==========
        case 'obtener_prestamos_activos':
            try {
                $sql = "SELECT pl.id_prestamo, pl.id_llave, pl.id_aula, pl.usuario_retira, pl.documento, pl.hora_prestamo, l.numero_llave, a.nombre as nombre_aula FROM prestamos_llaves pl 
                        JOIN llaves l ON pl.id_llave = l.id_llave 
                        JOIN aulas a ON pl.id_aula = a.id_aula 
                        WHERE pl.estado = 'Prestada' 
                        ORDER BY pl.fecha_prestamo DESC";
                
                $result = $db->query($sql);
                
                if (!$result) {
                    throw new Exception("Error en query: " . $db->error);
                }
                
                $prestamos = [];
                while ($row = $result->fetch_assoc()) {
                    $prestamos[] = $row;
                }
                
                $response = ['success' => true, 'data' => $prestamos];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
            break;

        // ========== OBTENER HISTORIAL ==========
        case 'obtener_historial':
            try {
                $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
                $fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');
                
                $sql = $db->prepare("SELECT pl.*, a.nombre as nombre_aula, l.numero_llave FROM prestamos_llaves pl 
                                    JOIN aulas a ON pl.id_aula = a.id_aula 
                                    JOIN llaves l ON pl.id_llave = l.id_llave 
                                    WHERE pl.fecha_prestamo BETWEEN ? AND ? 
                                    ORDER BY pl.fecha_prestamo DESC");
                
                $sql->bind_param("ss", $fecha_inicio, $fecha_fin);
                $sql->execute();
                $result = $sql->get_result();
                
                $historial = [];
                while ($row = $result->fetch_assoc()) {
                    $historial[] = $row;
                }
                $sql->close();
                
                $response = ['success' => true, 'data' => $historial];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
            break;

        // ========== OBTENER ESTADÍSTICAS ==========
        case 'obtener_estadisticas':
            try {
                $stats = [];
                
                // Total de aulas activas
                $result = $db->query("SELECT COUNT(*) as total FROM aulas WHERE activa = 1");
                $stats['total_aulas'] = $result->fetch_assoc()['total'];
                
                // Total de llaves
                $result = $db->query("SELECT COUNT(*) as total FROM llaves");
                $stats['total_llaves'] = $result->fetch_assoc()['total'];
                
                // Llaves disponibles
                $result = $db->query("SELECT COUNT(*) as total FROM llaves WHERE disponible = 1");
                $stats['llaves_disponibles'] = $result->fetch_assoc()['total'];
                
                // Préstamos activos
                $result = $db->query("SELECT COUNT(*) as total FROM prestamos_llaves WHERE estado = 'Prestada'");
                $stats['prestamos_activos'] = $result->fetch_assoc()['total'];
                
                // Préstamos hoy
                $result = $db->query("SELECT COUNT(*) as total FROM prestamos_llaves WHERE fecha_prestamo = CURDATE() AND estado = 'Prestada'");
                $stats['prestamos_hoy'] = $result->fetch_assoc()['total'];
                
                $response = ['success' => true, 'data' => $stats];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
            break;

        default:
            $response = ['success' => false, 'message' => 'Acción no reconocida: ' . $action];
    }
}

echo json_encode($response);
?>
