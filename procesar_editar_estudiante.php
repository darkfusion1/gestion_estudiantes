<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $correo = $_POST['correo'];

    // Preparar la consulta
    $query = "UPDATE estudiantes SET nombre = ?, edad = ?, correo = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        die("Error al preparar la consulta: " . $mysqli->error);
    }

    // Bind de los parámetros
    $stmt->bind_param("sisi", $nombre, $edad, $correo, $id);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: listar_estudiantes.php");
        exit(); // Asegúrate de llamar exit() después de header()
    } else {
        echo "Error al ejecutar la consulta: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
