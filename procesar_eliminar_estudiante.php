<?php
include 'conexion.php';

$id = $_GET['id'];

$query = "DELETE FROM estudiantes WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
header('Location: listar_estudiantes.php');
?>
