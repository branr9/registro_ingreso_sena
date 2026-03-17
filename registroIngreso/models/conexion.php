<?php
// Crear la conexión con la base de datos
$servidor = "localhost";           // host MySQL, suele ser localhost
$usuario = "root";                  // usuario de la base de datos
$password = "";                     // contraseña (revisar si hay una)
$base_datos = "conexionprueba";     // nombre de la base de datos

// Conexión sin especificar puerto (usa puerto por defecto 3306)
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error() . "<br>Asegúrate de que MySQL está ejecutándose en XAMPP.");
}

// Establecer charset UTF-8
mysqli_set_charset($conexion, "utf8");
?>
