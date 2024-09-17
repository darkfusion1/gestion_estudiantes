<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-5">
        <h1>Listado de Estudiantes</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Correo</th>
                    <th>Cursos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para listar estudiantes con cursos
                $query = "SELECT e.id, e.nombre, e.edad, e.correo, GROUP_CONCAT(c.nombre SEPARATOR ', ') AS cursos
                          FROM estudiantes e
                          LEFT JOIN estudiantes_cursos ec ON e.id = ec.estudiante_id
                          LEFT JOIN cursos c ON ec.curso_id = c.id
                          GROUP BY e.id";
                $result = $mysqli->query($query);

                if (!$result) {
                    die("Error en la consulta: " . $mysqli->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['nombre']}</td>";
                    echo "<td>{$row['edad']}</td>";
                    echo "<td>{$row['correo']}</td>";
                    echo "<td>{$row['cursos']}</td>";
                    echo "<td>
                            <a href='#' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-id='{$row['id']}' data-nombre='{$row['nombre']}' data-edad='{$row['edad']}' data-correo='{$row['correo']}'>Editar</a>
                            <a href='procesar_eliminar_estudiante.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este estudiante?\")'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Botón para eliminar estudiantes de un curso específico -->
        <form action="procesar_eliminar_por_curso.php" method="post" class="mt-4">
            <div class="mb-3">
                <label for="curso_id" class="form-label">Selecciona el Curso</label>
                <select id="curso_id" name="curso_id" class="form-select" required>
                    <?php
                    // Obtener lista de cursos
                    $curso_query = "SELECT * FROM cursos";
                    $curso_result = $mysqli->query($curso_query);
                    
                    if (!$curso_result) {
                        die("Error en la consulta: " . $mysqli->error);
                    }
                    
                    while ($curso_row = $curso_result->fetch_assoc()) {
                        echo "<option value='{$curso_row['id']}'>{$curso_row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger">Eliminar Estudiantes del Curso</button>
        </form>

        <!-- Estadísticas -->
        <h1 class="mt-5">Estadísticas</h1>
        <div class="row">
            <?php
            // Total estudiantes
            $total_estudiantes_query = "SELECT COUNT(*) as total_estudiantes FROM estudiantes";
            $total_estudiantes_result = $mysqli->query($total_estudiantes_query);
            if (!$total_estudiantes_result) {
                die('Error en la consulta de total estudiantes: ' . $mysqli->error);
            }
            $total_estudiantes = $total_estudiantes_result->fetch_assoc()['total_estudiantes'];

            // Total cursos
            $total_cursos_query = "SELECT COUNT(*) as total_cursos FROM cursos";
            $total_cursos_result = $mysqli->query($total_cursos_query);
            if (!$total_cursos_result) {
                die('Error en la consulta de total cursos: ' . $mysqli->error);
            }
            $total_cursos = $total_cursos_result->fetch_assoc()['total_cursos'];

            // Estudiantes por curso
            $estudiantes_por_curso_query = "SELECT c.nombre as curso, COUNT(ec.estudiante_id) as total_estudiantes
                                            FROM cursos c
                                            LEFT JOIN estudiantes_cursos ec ON c.id = ec.curso_id
                                            GROUP BY c.id";
            $estudiantes_por_curso_result = $mysqli->query($estudiantes_por_curso_query);
            if (!$estudiantes_por_curso_result) {
                die('Error en la consulta de estudiantes por curso: ' . $mysqli->error);
            }

            // Cursos por estudiante
            $cursos_por_estudiante_query = "SELECT e.nombre as estudiante, COUNT(ec.curso_id) as total_cursos
                                            FROM estudiantes e
                                            LEFT JOIN estudiantes_cursos ec ON e.id = ec.estudiante_id
                                            GROUP BY e.id";
            $cursos_por_estudiante_result = $mysqli->query($cursos_por_estudiante_query);
            if (!$cursos_por_estudiante_result) {
                die('Error en la consulta de cursos por estudiante: ' . $mysqli->error);
            }

            // Promedio edad
            $promedio_edad_query = "SELECT AVG(edad) as promedio_edad FROM estudiantes";
            $promedio_edad_result = $mysqli->query($promedio_edad_query);
            if (!$promedio_edad_result) {
                die('Error en la consulta de promedio de edad: ' . $mysqli->error);
            }
            $promedio_edad = $promedio_edad_result->fetch_assoc()['promedio_edad'];
            ?>

            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Estudiantes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_estudiantes; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Cursos</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_cursos; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Promedio Edad</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo intval($promedio_edad); ?> años</h5>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mt-5">Estudiantes por Curso</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Total Estudiantes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $estudiantes_por_curso_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['curso']}</td>";
                    echo "<td>{$row['total_estudiantes']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="mt-5">Cursos por Estudiante</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Total Cursos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $cursos_por_estudiante_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['estudiante']}</td>";
                    echo "<td>{$row['total_cursos']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <!-- Modal para editar estudiante -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="procesar_editar_estudiante.php" method="post">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_edad" class="form-label">Edad</label>
                            <input type="number" class="form-control" id="edit_edad" name="edad" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="edit_correo" name="correo" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para manejar el modal de edición
        document.addEventListener('DOMContentLoaded', function () {
            var editModal = document.getElementById('editModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var nombre = button.getAttribute('data-nombre');
                var edad = button.getAttribute('data-edad');
                var correo = button.getAttribute('data-correo');

                var modal = editModal.querySelector('form');
                modal.querySelector('#edit_id').value = id;
                modal.querySelector('#edit_nombre').value = nombre;
                modal.querySelector('#edit_edad').value = edad;
                modal.querySelector('#edit_correo').value = correo;
            });
        });
    </script>
</body>
</html>
