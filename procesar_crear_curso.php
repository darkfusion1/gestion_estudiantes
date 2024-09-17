<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Preparar la consulta SQL para insertar el nuevo curso
    $query = "INSERT INTO cursos (nombre, descripcion) VALUES (?, ?)";

    // Preparar la sentencia
    if ($stmt = $mysqli->prepare($query)) {
        // Vincular los parámetros a la sentencia preparada
        $stmt->bind_param("ss", $nombre, $descripcion);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Redirigir a la lista de cursos con un mensaje de éxito
            header("Location: listar_cursos.php?mensaje=curso_creado");
            exit;
        } else {
            echo "Error al crear el curso: " . $stmt->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $mysqli->error;
    }
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>
