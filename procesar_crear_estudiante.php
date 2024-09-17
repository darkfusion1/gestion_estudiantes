<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $email = $_POST['email'];

    // Validar los datos
    if (!empty($nombre) && !empty($edad) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Preparar la consulta SQL para insertar el nuevo estudiante
        $query = "INSERT INTO estudiantes (nombre, edad, correo) VALUES (?, ?, ?)";

        // Preparar la sentencia
        if ($stmt = $mysqli->prepare($query)) {
            // Vincular los parámetros a la sentencia preparada
            $stmt->bind_param("sis", $nombre, $edad, $email);  // "s" para string, "i" para integer

            // Ejecutar la sentencia
            if ($stmt->execute()) {
                // Redirigir a la lista de estudiantes con un mensaje de éxito
                header("Location: listar_estudiantes.php?mensaje=estudiante_creado");
                exit;
            } else {
                echo "Error al crear el estudiante: " . $stmt->error;
            }

            // Cerrar la sentencia
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $mysqli->error;
        }
    } else {
        echo "Error: Datos inválidos.";
    }
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>
