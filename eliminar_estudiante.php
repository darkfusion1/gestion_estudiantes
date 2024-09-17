<?php
include 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM estudiantes WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {
        echo '<script>alert("Estudiante eliminado correctamente."); window.location.href = "listar_estudiantes.php";</script>';
    } else {
        echo "Error al eliminar estudiante: " . $conexion->error;
    }
}
?>
