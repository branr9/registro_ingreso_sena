<?php
require_once __DIR__ . "/../models/conexion.php";
require_once __DIR__ . "/../models/modelKey.php";

class KeyController {
    private $modelKey;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->modelKey = new ModelKey($conexion);
    }

    // ========== AULAS ==========

    // Listar todas las aulas
    public function listarAulas() {
        return $this->modelKey->obtenerTodasLasAulas();
    }

    // Obtener aula específica
    public function obtenerAula($id) {
        return $this->modelKey->obtenerAulaPorId($id);
    }

    // Registrar nueva aula
    public function registrarAula() {
        if (!empty($_POST['action']) && $_POST['action'] === 'crear_aula') {
            if (!empty($_POST['nombre']) && !empty($_POST['llaves'])) {
                $nombre = trim($_POST['nombre']);
                $descripcion = trim($_POST['descripcion'] ?? '');
                $capacidad = intval($_POST['capacidad'] ?? 0);
                $total_llaves = intval($_POST['llaves']);

                if ($total_llaves <= 0) {
                    return ['success' => false, 'message' => 'El número de llaves debe ser mayor a 0'];
                }

                $resultado = $this->modelKey->crearAula($nombre, $descripcion, $capacidad, $total_llaves);

                if ($resultado) {
                    // Obtener ID de la aula creada
                    $id_aula = $this->conexion->insert_id;
                    
                    // Crear las llaves asociadas
                    for ($i = 1; $i <= $total_llaves; $i++) {
                        $this->modelKey->crearLlave($id_aula, "LLV-" . str_pad($id_aula, 3, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT));
                    }

                    return ['success' => true, 'message' => 'Aula registrada correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al registrar el aula'];
                }
            } else {
                return ['success' => false, 'message' => 'El nombre y número de llaves son obligatorios'];
            }
        }
        return '';
    }

    // Actualizar aula
    public function actualizarAula($id) {
        if (!empty($_POST['action']) && $_POST['action'] === 'actualizar_aula') {
            if (!empty($_POST['nombre']) && !empty($_POST['llaves'])) {
                $nombre = trim($_POST['nombre']);
                $descripcion = trim($_POST['descripcion'] ?? '');
                $capacidad = intval($_POST['capacidad'] ?? 0);
                $total_llaves = intval($_POST['llaves']);

                $resultado = $this->modelKey->actualizarAula($id, $nombre, $descripcion, $capacidad, $total_llaves);

                if ($resultado) {
                    return ['success' => true, 'message' => 'Aula actualizada correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al actualizar el aula'];
                }
            } else {
                return ['success' => false, 'message' => 'El nombre y número de llaves son obligatorios'];
            }
        }
        return '';
    }

    // Eliminar aula
    public function eliminarAula($id) {
        $resultado = $this->modelKey->eliminarAula($id);

        if ($resultado) {
            return ['success' => true, 'message' => 'Aula eliminada correctamente'];
        } else {
            return ['success' => false, 'message' => 'No se puede eliminar el aula si hay llaves prestadas'];
        }
    }

    // ========== LLAVES ==========

    // Obtener llaves de un aula
    public function obtenerLlavesAula($id_aula) {
        return $this->modelKey->obtenerLlavesPorAula($id_aula);
    }

    // Obtener llaves disponibles de un aula
    public function obtenerLlavesDisponiblesAula($id_aula) {
        return $this->modelKey->obtenerLlavesDisponibles($id_aula);
    }

    // ========== PRÉSTAMOS ==========

    // Registrar préstamo
    public function registrarPrestamo() {
        if (!empty($_POST['action']) && $_POST['action'] === 'registrar_prestamo') {
            if (!empty($_POST['id_llave']) && !empty($_POST['id_usuario']) && !empty($_POST['id_aula'])) {
                $id_llave = intval($_POST['id_llave']);
                $id_usuario = intval($_POST['id_usuario']);
                $id_aula = intval($_POST['id_aula']);

                // Verificar que la llave está disponible
                $llave = $this->modelKey->obtenerLlavePorId($id_llave);
                if (!$llave || $llave['disponible'] == 0) {
                    return ['success' => false, 'message' => 'La llave no está disponible'];
                }

                $resultado = $this->modelKey->registrarPrestamo($id_llave, $id_usuario, $id_aula);

                if ($resultado) {
                    return ['success' => true, 'message' => 'Préstamo registrado correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al registrar el préstamo'];
                }
            } else {
                return ['success' => false, 'message' => 'Datos incompletos'];
            }
        }
        return '';
    }

    // Devolver llave
    public function devolverLlave() {
        if (!empty($_POST['action']) && $_POST['action'] === 'devolver_llave') {
            if (!empty($_POST['id_llave']) && !empty($_POST['id_aula'])) {
                $id_llave = intval($_POST['id_llave']);
                $id_aula = intval($_POST['id_aula']);

                $resultado = $this->modelKey->devolverLlave($id_llave, $id_aula);

                if ($resultado) {
                    return ['success' => true, 'message' => 'Llave devuelta correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al devolver la llave'];
                }
            } else {
                return ['success' => false, 'message' => 'Datos incompletos'];
            }
        }
        return '';
    }

    // Obtener historial de préstamos
    public function obtenerHistorial($id_aula = null) {
        return $this->modelKey->obtenerHistorialPrestamos($id_aula);
    }

    // Obtener préstamos activos
    public function obtenerPrestamosActivos() {
        return $this->modelKey->obtenerPrestamosActivos();
    }

    // Obtener estadísticas
    public function obtenerEstadisticas() {
        return $this->modelKey->obtenerEstadisticas();
    }
}
?>
