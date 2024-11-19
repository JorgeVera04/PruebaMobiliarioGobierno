<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Mobiliarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vista/styles.css">
</head>

<body>

    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border-bottom">
        <div class="container">
                <div class="d-flex justify-content-center align-items-center">
                    <!-- Logo -->
                    <img src="https://seeklogo.com/images/O/oaxaca-gobierno-del-estado-logo-D127C58E05-seeklogo.com.png" 
                         alt="Logo Oaxaca" width="80" class="me-3">
                    
                    <!-- Títulos -->
                    <div>
                        <h1 class="header-title">GOBIERNO DEL ESTADO DE OAXACA</h1>
                        <p class="header-subtitle">CONTROL DE MOBILIARIO</p>
                    </div>
                </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=dependencias">Dependencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=empleados">Empleados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=mostrarMobiliarioAsignado">Mobiliario</a> <!-- Enlace para acceder a la vista de mobiliario -->
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="index.php?action=login">Iniciar Sesión</a> <!-- Enlace para iniciar sesión -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    

    <div class="container my-5">
        <h2 class="text-center mb-4">Administración de Mobiliarios</h2>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?>" role="alert">
                <?= $_SESSION['mensaje'] ?>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>



        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Mobiliario desasignado exitosamente.
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
            <div class="alert alert-danger" role="alert">
                Hubo un error al desasignar el mobiliario.
            </div>
        <?php endif; ?>


        <!-- asignar mobiliario -->

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#assignMobiliarioModal">
    Asignar Mobiliario
</button>


        <!-- Botón para agregar mobiliario -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addMobiliarioModal">Agregar
            Mobiliario</button>

        <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#eliminarMobiliarioModal">Eliminar Mobiliario</button>
        
        <div class="text-end mb-3">
        <a href="index.php?action=generarPDF&id_empleado=<?= isset($_GET['id_empleado']) ? $_GET['id_empleado'] : '' ?>&categoria=<?= isset($_GET['categoria']) ? $_GET['categoria'] : '' ?>&estado=<?= isset($_GET['estado']) ? $_GET['estado'] : '' ?>" class="btn btn-danger" target="_blank">Generar PDF</a>

    </div>
            
        <!-- Filtro de búsqueda -->
        <form method="GET" action="index.php" class="mb-3">
            <input type="hidden" name="action" value="adminMobiliario">
            <div class="d-flex">
                <select name="id_empleado" class="form-select me-2" onchange="this.form.submit()">
                    <option value="">Filtrar por Empleado</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?= $empleado['id_empleado'] ?>" <?= isset($_GET['id_empleado']) && $_GET['id_empleado'] == $empleado['id_empleado'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($empleado['nombre_empleado']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="categoria" class="form-select" onchange="this.form.submit()">
            <option value="">Filtrar por Categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['categoria'] ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $categoria['categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="estado" class="form-select" onchange="this.form.submit()">
            <option value="">Todos</option>
            <option value="asignado" <?= isset($_GET['estado']) && $_GET['estado'] == 'asignado' ? 'selected' : '' ?>>Asignados</option>
            <option value="desasignado" <?= isset($_GET['estado']) && $_GET['estado'] == 'desasignado' ? 'selected' : '' ?>>Desasignados</option>
        </select>


            </div>
        </form>

        <!-- Mostrar total de registros -->
        <div class="mb-3">
            <h5>TOTAL HISTORIAL: <span class="badge bg-secondary"><?= $resultados->num_rows ?></span>
            </h5>
        </div>

        <!-- Tabla de mobiliarios -->
        <table class="table table-bordered table-hover">
        <thead>
                <tr>
                    <th>N°</th>
                    <th>Empleado</th>
                    <th>Mobiliario</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Fecha de Asignación</th>
                    <th>Fecha de Desasignación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $contador = 1; ?>
                <?php while ($row = $resultados->fetch_assoc()): ?>
                    <tr>
                        <td><?= $contador++ ?></td>
                        <td><?= htmlspecialchars($row["nombre_empleado"]) ?></td>
                        <td><?= htmlspecialchars($row["mobiliario"]) ?></td>
                        <td><?= htmlspecialchars($row["categoria"]) ?></td>
                        <td><?= htmlspecialchars($row["estado"]) ?></td>
                        <td><?= htmlspecialchars($row["fecha_asignacion"]) ?></td>
                        <td><?= $row["fecha_desasignacion"] ? htmlspecialchars($row["fecha_desasignacion"]) : 'No desasignado' ?>
                        </td>
                        <td>
                            <!-- Botón para editar -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAsignacionModal"
    onclick="loadEditAsignacionModal(<?= $row['id_historial'] ?>, <?= $row['id_empleado'] ?>, <?= $row['id_mobiliario'] ?>)">
    Editar
</button>


                            <!-- Botón para desasignar -->
                            <form action="index.php?action=desasignarMobiliario" method="POST" style="display:inline;">
                                <input type="hidden" name="id_mobiliario"
                                    value="<?= htmlspecialchars($row['id_mobiliario']) ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Desasignar</button>
                            </form>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- Modal para Asignar Mobiliario a Empleado -->
<!-- Modal para Asignar Mobiliario a Empleado -->
<div class="modal fade" id="assignMobiliarioModal" tabindex="-1" aria-labelledby="assignMobiliarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="index.php?action=asignarMobiliario" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignMobiliarioModalLabel">Asignar Mobiliario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="empleado" class="form-label">Empleado</label>
                        <select name="id_empleado" class="form-select" id="empleado" required>
                            <option value="">-- Selecciona un Empleado --</option>
                            <?php foreach ($empleados as $empleado): ?>
                                <option value="<?= $empleado['id_empleado'] ?>">
                                    <?= htmlspecialchars($empleado['nombre_empleado']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mobiliario" class="form-label">Mobiliario</label>
                        <select name="id_mobiliario" class="form-select" id="mobiliario" required>
                            <option value="">-- Selecciona un Mobiliario --</option>
                            <?php foreach ($mobiliarios as $mobiliario): ?>
                                <option value="<?= $mobiliario['id_mobiliario'] ?>">
                                    <?= htmlspecialchars($mobiliario['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">Asignar</button>
                </div>
            </div>
        </form>
    </div>
</div>



    <!-- Modal para Agregar Mobiliario -->
    <div class="modal fade" id="addMobiliarioModal" tabindex="-1" aria-labelledby="addMobiliarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMobiliarioModalLabel">Agregar Mobiliario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?action=agregarMobiliario" method="POST">
                        <!-- Campos para agregar mobiliario -->
                        <div class="mb-3">
                            <label for="mobiliario" class="form-label">Descripción del Mobiliario</label>
                            <input type="text" class="form-control" id="mobiliario" name="descripcion"
                                placeholder="Ej: Silla de oficina" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <input type="text" class="form-control" id="categoria" name="categoria"
                                placeholder="Ej: Oficina" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="Nuevo">Nuevo</option>
                                <option value="Usado">Usado</option>
                                <option value="Dañado">Dañado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dependencia" class="form-label">Dependencia</label>
                            <select class="form-select" id="dependencia" name="id_dependencia" required>
                                <?php foreach ($dependencias as $dependencia): ?>
                                    <option value="<?= $dependencia['id_dependencia'] ?>">
                                        <?= htmlspecialchars($dependencia['nombre_dependencia']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para abrir el modal -->

<!-- Modal para eliminar mobiliario -->
<div class="modal fade" id="eliminarMobiliarioModal" tabindex="-1" aria-labelledby="eliminarMobiliarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarMobiliarioModalLabel">Eliminar Mobiliario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?action=eliminarMobiliario" method="POST">
                    <div class="mb-3">
                        <label for="mobiliario" class="form-label">Selecciona el Mobiliario</label>
                        <select class="form-select" id="mobiliario" name="id_mobiliario" required>
                            <option value="">-- Selecciona un Mobiliario --</option>
                            <?php foreach ($mobiliarios as $mobiliario): ?>
                                <option value="<?= $mobiliario['id_mobiliario'] ?>">
                                    <?= htmlspecialchars($mobiliario['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal para Editar Asignacion -->
<!-- Modal para Editar Asignación -->
<div class="modal fade" id="editAsignacionModal" tabindex="-1" aria-labelledby="editAsignacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAsignacionModalLabel">Editar Asignación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?action=editarAsignacion" method="POST">
                    <input type="hidden" name="id_historial" id="edit_id_historial">

                    <div class="mb-3">
                        <label for="edit_empleado" class="form-label">Empleado</label>
                        <select class="form-select" id="edit_empleado" name="id_empleado" required>
                            <?php foreach ($empleados as $empleado): ?>
                                <option value="<?= $empleado['id_empleado'] ?>">
                                    <?= htmlspecialchars($empleado['nombre_empleado']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_mobiliario" class="form-label">Mobiliario</label>
                        <select class="form-select" id="edit_mobiliario" name="id_mobiliario" required>
                            <?php foreach ($mobiliarios as $mobiliario): ?>
                                <option value="<?= $mobiliario['id_mobiliario'] ?>">
                                    <?= htmlspecialchars($mobiliario['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para cargar los datos en el modal de edición
        function editMobiliario(mobiliario) {
            document.getElementById('edit_id_mobiliario').value = mobiliario.id_mobiliario;
            document.getElementById('edit_empleado').value = mobiliario.id_empleado;
            document.getElementById('edit_mobiliario').value = mobiliario.mobiliario;
            document.getElementById('edit_categoria').value = mobiliario.categoria;
            document.getElementById('edit_estado').value = mobiliario.estado;            
        }

        function loadEditAsignacionModal(idHistorial, idEmpleado, idMobiliario) {
    document.getElementById('edit_id_historial').value = idHistorial;
    document.getElementById('edit_empleado').value = idEmpleado;
    document.getElementById('edit_mobiliario').value = idMobiliario;
}

    </script>




</body>

</html>