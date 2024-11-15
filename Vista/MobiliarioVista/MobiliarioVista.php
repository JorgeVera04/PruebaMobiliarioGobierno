<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Mobiliarios Asignados a Empleados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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
    <h2 class="text-center mb-4">Listado de Mobiliarios Asignados a Empleados</h2>
    
    <!-- Menús desplegables de filtros -->
    <form method="GET" action="index.php">
        <div class="row mb-4">
            <!-- Filtro por Empleado -->
            <div class="col-md-5">
                <select name="id_empleado" class="form-select" onchange="this.form.submit()">
                    <option value="">Filtrar por Empleado</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?= $empleado['id_empleado'] ?>" <?= isset($_GET['id_empleado']) && $_GET['id_empleado'] == $empleado['id_empleado'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($empleado['nombre_empleado']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtro por Categoría -->
            <div class="col-md-5">
                <select name="categoria" class="form-select" onchange="this.form.submit()">
                    <option value="">Filtrar por Categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria['categoria']) ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $categoria['categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <input type="hidden" name="action" value="mostrarMobiliarioAsignado">
            </div>
        </div>
    </form>

    <!-- Botón para generar PDF -->
    <div class="text-end mb-3">
        <a href="index.php?action=generarPDF&id_empleado=<?= isset($_GET['id_empleado']) ? $_GET['id_empleado'] : '' ?>&categoria=<?= isset($_GET['categoria']) ? $_GET['categoria'] : '' ?>" 
           class="btn btn-danger" target="_blank">Generar PDF</a>
    </div>

    <!-- Contador de resultados -->
    <div class="mb-3">
        <h5>Total de Mobiliarios Asignados: <span class="badge bg-secondary"><?= $resultados->num_rows ?></span></h5>
    </div>

    <!-- Tabla de Mobiliario Asignado -->
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
            </tr>
        </thead>
        <tbody>
            <?php if ($resultados && $resultados->num_rows > 0): ?>
                <?php $contador = 1; // Contador para enumerar los registros ?>
                <?php while ($row = $resultados->fetch_assoc()): ?>
                    <tr>
                        <td><?= $contador++ ?></td> <!-- Contador por registro -->
                        <td><?= htmlspecialchars($row["nombre_empleado"]) ?></td>
                        <td><?= htmlspecialchars($row["mobiliario"]) ?></td>
                        <td><?= htmlspecialchars($row["categoria"]) ?></td>
                        <td><?= htmlspecialchars($row["estado"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row["fecha_asignacion"]) ?></td>
                        <td class="text-center"><?= $row["fecha_desasignacion"] ? htmlspecialchars($row["fecha_desasignacion"]) : 'No desasignado' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No se encontraron asignaciones de mobiliario.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
