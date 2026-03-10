<?php
// Crear la conexión con la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";
<<<<<<< HEAD
$base_datos = "conexionprueba2";
=======
$base_datos = "conexionprueba";
>>>>>>> main

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
