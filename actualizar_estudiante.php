<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $correo = $_POST['correo'];

    $query = "UPDATE estudiantes SET nombre = ?, edad = ?, correo = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sisi", $nombre, $edad, $correo, $id);
    $stmt->execute();
    
    header("Location: listar_estudiantes.php");
}
?>
