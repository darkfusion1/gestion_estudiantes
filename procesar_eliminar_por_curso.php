<?php 
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curso_id = $_POST['curso_id'];

    // Eliminar todos los registros de la tabla de relación
    $delete_query = "DELETE FROM estudiante_curso WHERE curso_id = ?";
    $stmt = $mysqli->prepare($delete_query);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();

    // Opcional: Eliminar los estudiantes si no están inscritos en ningún curso
    $cleanup_query = "DELETE e FROM estudiantes e
                      LEFT JOIN estudiante_curso ec ON e.id = ec.estudiante_id
                      WHERE ec.estudiante_id IS NULL";
    $mysqli->query($cleanup_query);

    header("Location: listar_estudiantes.php");
    exit();
}
?>
