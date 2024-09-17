<?php
$host = "localhost";  // Nombre del host
$user = "root";       // Usuario de la base de datos
$pass = "";           // Contraseña de la base de datos (si la tienes, agrégala aquí)
$db = "gestion_estudiantes";  // Nombre de tu base de datos

// Crear conexión
$mysqli = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}
?>
