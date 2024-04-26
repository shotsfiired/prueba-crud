<?php

$servername = "localhost";
$username = "root";
$contraseña = ""; 
$basededatos = "crud_justin"; 

$conn = new mysqli($servername, $username, $contraseña, $basededatos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
