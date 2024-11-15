<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Mobiliarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://seeklogo.com/images/O/oaxaca-gobierno-del-estado-logo-D127C58E05-seeklogo.com.png" alt="Logo Oaxaca" width="50">
                Gobierno de Oaxaca - Administración de Empleados
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=dependencias">Dependencias</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=mobiliario">Mobiliario</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=logout">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container my-5">
    <h2 class="text-center mb-4">Administración de Mobiliarios</h2>

    <!-- Botón para agregar mobiliario -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addMobiliarioModal">Agregar Mobiliario</button>

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
        </div>
    </form>

    <!-- Mostrar total de registros -->
    <div class="mb-3">
        <h5>Total de Mobiliarios Asignados: <span class="badge bg-secondary"><?= $resultados->num_rows ?></span></h5>
    </div>

    <!-- Tabla de mobiliarios -->
    <table class="table table-bordered table-hover">
        <thead class="table-light">
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
                    <td><?= $row["fecha_desasignacion"] ? htmlspecialchars($row["fecha_desasignacion"]) : 'No desasignado' ?></td>
                    <td>
                        <!-- Botón para editar -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMobiliarioModal" onclick="editMobiliario(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">Editar</button>
                        
                        <!-- Botón para desasignar -->
                        <!-- Formulario para Desasignar -->
<form action="index.php?action=desasignarMobiliario" method="POST" style="display:inline;">
    <input type="hidden" name="id_mobiliario" value="<?= $row['id_mobiliario'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">Desasignar</button>
</form>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal para Agregar Mobiliario -->
<div class="modal fade" id="addMobiliarioModal" tabindex="-1" aria-labelledby="addMobiliarioModalLabel" aria-hidden="true">
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
                        <label for="empleado" class="form-label">Empleado</label>
                        <select class="form-select" id="empleado" name="id_empleado" required>
                            <?php foreach ($empleados as $empleado): ?>
                                <option value="<?= $empleado['id_empleado'] ?>"><?= htmlspecialchars($empleado['nombre_empleado']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mobiliario" class="form-label">Mobiliario</label>
                        <input type="text" class="form-control" id="mobiliario" name="mobiliario" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Usado">Usado</option>
                            <option value="Nuevo">Nuevo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Mobiliario -->
<div class="modal fade" id="editMobiliarioModal" tabindex="-1" aria-labelledby="editMobiliarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMobiliarioModalLabel">Editar Mobiliario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMobiliarioForm" action="index.php?action=editarMobiliario" method="POST">
                    <input type="hidden" id="edit_id_mobiliario" name="id_mobiliario">
                    <div class="mb-3">
                        <label for="edit_empleado" class="form-label">Empleado</label>
                        <select class="form-select" id="edit_empleado" name="id_empleado" required>
                            <?php foreach ($empleados as $empleado): ?>
                                <option value="<?= $empleado['id_empleado'] ?>"><?= htmlspecialchars($empleado['nombre_empleado']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_mobiliario" class="form-label">Mobiliario</label>
                        <input type="text" class="form-control" id="edit_mobiliario" name="mobiliario" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_categoria" class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="edit_categoria" name="categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado" name="estado" required>
                            <option value="Usado">Usado</option>
                            <option value="Nuevo">Nuevo</option>
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
</script>

</body>
</html>
