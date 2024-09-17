<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curso_id = $_POST['curso_id'];
    $estudiante_id = $_POST['estudiante_id'];

    // Agregar alumno al curso
    $query = "INSERT INTO estudiantes_cursos (estudiante_id, curso_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $estudiante_id, $curso_id);
    $stmt->execute();
    
    header("Location: listar_cursos.php");
}
?>
