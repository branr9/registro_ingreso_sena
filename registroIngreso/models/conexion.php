<?php
// Crear la conexión con la base de datos
$servidor = "localhost";           // host MySQL, suele ser localhost
$usuario = "root";                  // usuario de la base de datos
$password = "";                     // contraseña (revisar si hay una)
$base_datos = "conexionprueba";     // nombre de la base de datos
$puerto = 3307;                      // puerto por defecto de XAMPP en este equipo

// mysqli_connect permite pasar el puerto como quinto argumento
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos, $puerto);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
