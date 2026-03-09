<?php
// Crear la conexión con la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "conexionprueba2";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
