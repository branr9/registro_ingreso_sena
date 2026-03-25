<?php
// Clase para manejar la conexión con la base de datos
class Conexion {
    private $servidor = "localhost";
    private $usuario = "root";
    private $password = "root";
    private $base_datos = "nexus";
    private $conexion;

    public function conectar() {
        $this->conexion = mysqli_connect(
            $this->servidor, 
            $this->usuario, 
            $this->password, 
            $this->base_datos
        );

        if (!$this->conexion) {
            die(json_encode([
                'success' => false, 
                'message' => 'Conexión fallida: ' . mysqli_connect_error()
            ]));
        }

        // Establecer charset a utf8
        mysqli_set_charset($this->conexion, "utf8");

        return $this->conexion;
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function cerrar() {
        if ($this->conexion) {
            mysqli_close($this->conexion);
        }
    }
}
?>
