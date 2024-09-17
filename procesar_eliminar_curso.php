<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $curso_id = $_GET['id'];

    // Eliminar el curso
    $query = "DELETE FROM cursos WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    
    // Eliminar asociaciones del curso
    $query = "DELETE FROM estudiantes_cursos WHERE curso_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    
    header("Location: listar_cursos.php");
}
?>
