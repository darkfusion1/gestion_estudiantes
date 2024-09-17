<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $curso_id = $_GET['curso_id'];

    // Obtener la lista de estudiantes disponibles
    $query = "SELECT * FROM estudiantes";
    $result = $mysqli->query($query);

    if (!$result) {
        die("Error en la consulta: " . $mysqli->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Alumno al Curso</h1>
        <form action="procesar_agregar_alumno.php" method="post">
            <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
            <div class="mb-3">
                <label for="estudiante_id" class="form-label">Selecciona el Alumno</label>
                <select id="estudiante_id" name="estudiante_id" class="form-select" required>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
    </div>
</body>
</html>
