<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-5">
        <h1>Listado de Cursos</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Alumnos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM cursos";
                $result = $mysqli->query($query);

                if (!$result) {
                    die("Error en la consulta: " . $mysqli->error);
                }

                while ($row = $result->fetch_assoc()) {
                    $curso_id = $row['id'];
                    
                    // Obtener la lista de alumnos en el curso
                    $alumnos_query = "SELECT e.nombre FROM estudiantes e
                                      JOIN estudiantes_cursos ec ON e.id = ec.estudiante_id
                                      WHERE ec.curso_id = ?";
                    $stmt = $mysqli->prepare($alumnos_query);
                    $stmt->bind_param("i", $curso_id);
                    $stmt->execute();
                    $alumnos_result = $stmt->get_result();
                    
                    $alumnos = [];
                    while ($alumno = $alumnos_result->fetch_assoc()) {
                        $alumnos[] = $alumno['nombre'];
                    }
                    
                    // Convertir lista de alumnos en una cadena separada por comas
                    $alumnos_list = implode(", ", $alumnos);
                    
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['nombre']}</td>";
                    echo "<td>{$row['descripcion']}</td>";
                    echo "<td>{$alumnos_list}</td>";
                    echo "<td>
                            <a href='agregar_alumno.php?curso_id={$row['id']}' class='btn btn-success btn-sm'>Agregar Alumno</a>
                            <a href='procesar_eliminar_curso.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este curso?\")'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
