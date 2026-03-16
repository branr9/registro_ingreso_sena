<?php
$c = @mysqli_connect('localhost','root','', 'conexionprueba');
if ($c) {
    echo "OK\n";
} else {
    echo "Error: " . mysqli_connect_error() . "\n";
}
?>