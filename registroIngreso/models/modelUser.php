<?php
class ModelUser {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener todos los usuarios
    public function obtenerTodosLosUsuarios() {
        $sql = $this->conexion->query("SELECT * FROM usuarios");
        return $sql;
    }

    // Obtener un usuario por ID
    public function obtenerUsuarioPorId($id) {
        $sql = $this->conexion->query("SELECT * FROM usuarios WHERE Id_usuario='$id'");
        return $sql;
    }

    // Crear un nuevo usuario
    public function crearUsuario($nombre, $apellido, $fecha, $dni, $correo) {
        $sql = $this->conexion->query("INSERT INTO usuarios(nombre,apellido,fecha,Dni,correo) VALUES('$nombre','$apellido','$fecha','$dni','$correo')");
        return $sql;
    }

    // Actualizar un usuario existente
    public function actualizarUsuario($id, $nombre, $apellido, $fecha, $dni, $correo) {
        $sql = $this->conexion->query("UPDATE usuarios SET nombre='$nombre', apellido='$apellido', fecha='$fecha', Dni='$dni', correo='$correo' WHERE Id_usuario='$id'");
        return $sql;
    }

    // Eliminar un usuario
    public function eliminarUsuario($id) {
        $sql = $this->conexion->query("DELETE FROM usuarios WHERE Id_usuario='$id'");
        return $sql;
    }

    // Validar credenciales para login (correo + DNI)
    public function validarCredenciales($correo, $dni) {
        $stmt = $this->conexion->prepare("SELECT Id_usuario, nombre, apellido, correo, Dni FROM usuarios WHERE correo = ? AND Dni = ? LIMIT 1");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("ss", $correo, $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado ? $resultado->fetch_assoc() : null;
        $stmt->close();

        return $usuario;
    }
}
?>
