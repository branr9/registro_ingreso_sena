<?php
require_once __DIR__ . "/../models/conexion.php";
require_once __DIR__ . "/../models/modelUser.php";

class UsuarioController {
    private $modelUser;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->modelUser = new ModelUser($conexion);
    }

    // Listar todos los usuarios
    public function listarUsuarios() {
        return $this->modelUser->obtenerTodosLosUsuarios();
    }

    // Obtener un usuario específico
    public function obtenerUsuario($id) {
        return $this->modelUser->obtenerUsuarioPorId($id);
    }

    // Registrar un nuevo usuario
    public function registrarUsuario() {
        if (!empty($_POST['btnenviar'])) {
            if (!empty($_POST['nombre']) && 
                !empty($_POST['apellido']) && 
                !empty($_POST['fecha']) && 
                !empty($_POST['dni']) && 
                !empty($_POST['correo'])) {
                
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $fecha = $_POST['fecha'];
                $dni = $_POST['dni'];
                $correo = $_POST['correo'];
                
                $resultado = $this->modelUser->crearUsuario($nombre, $apellido, $fecha, $dni, $correo);
                
                if ($resultado == 1) {
                    header("Location: index.php");
                    exit();
                } else {
                    return "<div class='alert alert-danger'>Error al registrar el usuario</div>";
                }
            } else {
                return "<div class='alert alert-danger'>Datos incompletos - Por favor llena todos los campos</div>";
            }
        }
        return "";
    }

    // Modificar un usuario existente
    public function modificarUsuario($id) {
        if (!empty($_POST['btnenviar'])) {
            if (!empty($_POST['nombre']) && 
                !empty($_POST['apellido']) && 
                !empty($_POST['fecha']) && 
                !empty($_POST['dni']) && 
                !empty($_POST['correo'])) {
                
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $fecha = $_POST['fecha'];
                $dni = $_POST['dni'];
                $correo = $_POST['correo'];
                
                $resultado = $this->modelUser->actualizarUsuario($id, $nombre, $apellido, $fecha, $dni, $correo);
                
                if ($resultado == 1) {
                    header("Location: ../../index.php");
                    exit();
                } else {
                    return "<div class='alert alert-danger'>Error al modificar el usuario</div>";
                }
            } else {
                return "<div class='alert alert-danger'>Datos incompletos - Por favor llena todos los campos</div>";
            }
        }
        return "";
    }

    // Eliminar un usuario
    public function eliminarUsuario($id) {
        $resultado = $this->modelUser->eliminarUsuario($id);
        if ($resultado == 1) {
            header("Location: index.php");
            exit();
        } else {
            return "<div class='alert alert-danger'>Error al eliminar el usuario</div>";
        }
    }
}
?>
