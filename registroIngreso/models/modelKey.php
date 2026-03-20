<?php
class ModelKey {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // ========== AULAS ==========

    // Obtener todas las aulas
    public function obtenerTodasLasAulas() {
        $sql = "SELECT * FROM aulas ORDER BY nombre ASC";
        return $this->conexion->query($sql);
    }

    // Obtener aula por ID
    public function obtenerAulaPorId($id) {
        $sql = $this->conexion->prepare("SELECT * FROM aulas WHERE id_aula = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    // Crear nueva aula
    public function crearAula($nombre, $descripcion, $capacidad, $total_llaves) {
        $sql = $this->conexion->prepare("INSERT INTO aulas (nombre, descripcion, capacidad, total_llaves, disponibles) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("ssiii", $nombre, $descripcion, $capacidad, $total_llaves, $total_llaves);
        return $sql->execute();
    }

    // Actualizar aula
    public function actualizarAula($id, $nombre, $descripcion, $capacidad, $total_llaves) {
        $sql = $this->conexion->prepare("UPDATE aulas SET nombre = ?, descripcion = ?, capacidad = ?, total_llaves = ? WHERE id_aula = ?");
        $sql->bind_param("sssii", $nombre, $descripcion, $capacidad, $total_llaves, $id);
        return $sql->execute();
    }

    // Eliminar aula
    public function eliminarAula($id) {
        // Primero verificar que no hay préstamos activos
        $sql_check = $this->conexion->prepare("SELECT COUNT(*) as count FROM prestamos_llaves WHERE id_aula = ? AND estado = 'Prestada'");
        $sql_check->bind_param("i", $id);
        $sql_check->execute();
        $result = $sql_check->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            return false; // No se puede eliminar si hay llaves prestadas
        }

        $sql = $this->conexion->prepare("DELETE FROM aulas WHERE id_aula = ?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    // ========== LLAVES ==========

    // Obtener todas las llaves de una aula
    public function obtenerLlavesPorAula($id_aula) {
        $sql = $this->conexion->prepare("SELECT * FROM llaves WHERE id_aula = ? ORDER BY numero_llave ASC");
        $sql->bind_param("i", $id_aula);
        $sql->execute();
        return $sql->get_result();
    }

    // Obtener llaves disponibles de una aula
    public function obtenerLlavesDisponibles($id_aula) {
        $sql = $this->conexion->prepare("SELECT * FROM llaves WHERE id_aula = ? AND disponible = 1 ORDER BY numero_llave ASC");
        $sql->bind_param("i", $id_aula);
        $sql->execute();
        return $sql->get_result();
    }

    // Obtener llave por ID
    public function obtenerLlavePorId($id_llave) {
        $sql = $this->conexion->prepare("SELECT * FROM llaves WHERE id_llave = ?");
        $sql->bind_param("i", $id_llave);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    // Crear nueva llave
    public function crearLlave($id_aula, $numero_llave) {
        $sql = $this->conexion->prepare("INSERT INTO llaves (id_aula, numero_llave, disponible) VALUES (?, ?, 1)");
        $sql->bind_param("is", $id_aula, $numero_llave);
        return $sql->execute();
    }

    // ========== PRÉSTAMOS ==========

    // Registrar préstamo de llave
    public function registrarPrestamo($id_llave, $id_usuario, $id_aula) {
        $this->conexion->begin_transaction();

        try {
            // Insertar registro de préstamo
            $sql_prestamo = $this->conexion->prepare("INSERT INTO prestamos_llaves (id_llave, id_usuario, id_aula, estado, fecha_prestamo, hora_prestamo) VALUES (?, ?, ?, 'Prestada', CURDATE(), CURTIME())");
            $sql_prestamo->bind_param("iii", $id_llave, $id_usuario, $id_aula);
            $sql_prestamo->execute();

            // Actualizar estado de la llave como no disponible
            $sql_llave = $this->conexion->prepare("UPDATE llaves SET disponible = 0 WHERE id_llave = ?");
            $sql_llave->bind_param("i", $id_llave);
            $sql_llave->execute();

            // Actualizar contador de llaves disponibles del aula
            $sql_aula = $this->conexion->prepare("UPDATE aulas SET disponibles = disponibles - 1 WHERE id_aula = ?");
            $sql_aula->bind_param("i", $id_aula);
            $sql_aula->execute();

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }

    // Devolver llave
    public function devolverLlave($id_llave, $id_aula) {
        $this->conexion->begin_transaction();

        try {
            // Actualizar estado del préstamo a completado
            $sql_prestamo = $this->conexion->prepare("UPDATE prestamos_llaves SET estado = 'Devuelta', hora_devolucion = CURTIME(), fecha_devolucion = CURDATE() WHERE id_llave = ? AND estado = 'Prestada'");
            $sql_prestamo->bind_param("i", $id_llave);
            $sql_prestamo->execute();

            // Actualizar estado de la llave como disponible
            $sql_llave = $this->conexion->prepare("UPDATE llaves SET disponible = 1 WHERE id_llave = ?");
            $sql_llave->bind_param("i", $id_llave);
            $sql_llave->execute();

            // Actualizar contador de llaves disponibles del aula
            $sql_aula = $this->conexion->prepare("UPDATE aulas SET disponibles = disponibles + 1 WHERE id_aula = ?");
            $sql_aula->bind_param("i", $id_aula);
            $sql_aula->execute();

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }

    // Obtener historial de préstamos
    public function obtenerHistorialPrestamos($id_aula = null) {
        if ($id_aula) {
            $sql = $this->conexion->prepare("
                SELECT pl.*, u.nombre, u.apellido, a.nombre as nombre_aula, l.numero_llave 
                FROM prestamos_llaves pl 
                JOIN usuarios u ON pl.id_usuario = u.Id_usuario 
                JOIN aulas a ON pl.id_aula = a.id_aula 
                JOIN llaves l ON pl.id_llave = l.id_llave 
                WHERE pl.id_aula = ? 
                ORDER BY pl.fecha_prestamo DESC
            ");
            $sql->bind_param("i", $id_aula);
        } else {
            $sql = $this->conexion->query("
                SELECT pl.*, u.nombre, u.apellido, a.nombre as nombre_aula, l.numero_llave 
                FROM prestamos_llaves pl 
                JOIN usuarios u ON pl.id_usuario = u.Id_usuario 
                JOIN aulas a ON pl.id_aula = a.id_aula 
                JOIN llaves l ON pl.id_llave = l.id_llave 
                ORDER BY pl.fecha_prestamo DESC
            ");
            return $sql;
        }

        $sql->execute();
        return $sql->get_result();
    }

    // Obtener préstamos activos
    public function obtenerPrestamosActivos() {
        $sql = "
            SELECT pl.*, u.nombre, u.apellido, a.nombre as nombre_aula, l.numero_llave 
            FROM prestamos_llaves pl 
            JOIN usuarios u ON pl.id_usuario = u.Id_usuario 
            JOIN aulas a ON pl.id_aula = a.id_aula 
            JOIN llaves l ON pl.id_llave = l.id_llave 
            WHERE pl.estado = 'Prestada' 
            ORDER BY pl.fecha_prestamo DESC
        ";
        return $this->conexion->query($sql);
    }

    // Obtener estadísticas
    public function obtenerEstadisticas() {
        $stats = [
            'total_aulas' => 0,
            'total_llaves' => 0,
            'llaves_prestadas' => 0,
            'llaves_disponibles' => 0,
            'prestamos_hoy' => 0
        ];

        // Total de aulas
        $result = $this->conexion->query("SELECT COUNT(*) as count FROM aulas");
        $stats['total_aulas'] = $result->fetch_assoc()['count'];

        // Total de llaves
        $result = $this->conexion->query("SELECT COUNT(*) as count FROM llaves");
        $stats['total_llaves'] = $result->fetch_assoc()['count'];

        // Llaves prestadas
        $result = $this->conexion->query("SELECT COUNT(*) as count FROM llaves WHERE disponible = 0");
        $stats['llaves_prestadas'] = $result->fetch_assoc()['count'];

        // Llaves disponibles
        $stats['llaves_disponibles'] = $stats['total_llaves'] - $stats['llaves_prestadas'];

        // Préstamos hoy
        $result = $this->conexion->query("SELECT COUNT(*) as count FROM prestamos_llaves WHERE DATE(fecha_prestamo) = CURDATE()");
        $stats['prestamos_hoy'] = $result->fetch_assoc()['count'];

        return $stats;
    }
}
?>
