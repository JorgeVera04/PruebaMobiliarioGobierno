<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Dependencias - Gobierno de Oaxaca</title>
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
        <h2 class="text-center mb-4">Administración de Dependencias</h2>
        
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
        <?php endif; ?>

       
        
        <form method="GET" action="index.php" class="input-group mb-3">
    <input type="hidden" name="action" value="dependenciasAdmin">
    <select name="filtro" class="form-select">
        <option value="">Selecciona una opción de filtrado</option>
        <?php foreach ($ubicaciones as $ubicacion): ?>
            <option value="ubicacion:<?php echo $ubicacion['ubicacion']; ?>">
                Ubicación: <?php echo htmlspecialchars($ubicacion['ubicacion']); ?>
            </option>
        <?php endforeach; ?>
        <?php foreach ($estados as $estado): ?>
            <option value="estado:<?php echo $estado; ?>">
                Estado: <?php echo htmlspecialchars($estado); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit">Filtrar</button>
    
</form>

<div class="text-end mb-3">
            <a href="index.php?action=generar_pdf_dependencias&filtro=<?= urlencode(isset($_GET['filtro']) ? $_GET['filtro'] : '') ?>" class="btn btn-danger">Generar PDF</a>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre_dependencia']) ?></td>
                            <td><?= htmlspecialchars($row['ubicacion']) ?></td>
                            <td><?= htmlspecialchars($row['estado']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDependenciaModal" onclick="editDependencia(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">Editar</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No se encontraron dependencias</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Agregar Dependencia -->
    <div class="modal fade" id="addDependenciaModal" tabindex="-1" aria-labelledby="addDependenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDependenciaModalLabel">Agregar Nueva Dependencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?action=agregarDependencia" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Dependencia -->
    <div class="modal fade" id="editDependenciaModal" tabindex="-1" aria-labelledby="editDependenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDependenciaModalLabel">Editar Dependencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDependenciaForm" action="index.php?action=editarDependencia" method="POST">
                        <input type="hidden" id="edit_id_dependencia" name="id_dependencia">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="edit_ubicacion" name="ubicacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_estado" class="form-label">Estado</label>
                            <select class="form-select" id="edit_estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
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
        function editDependencia(dependencia) {
            document.getElementById('edit_id_dependencia').value = dependencia.id_dependencia;
            document.getElementById('edit_nombre').value = dependencia.nombre_dependencia;
            document.getElementById('edit_ubicacion').value = dependencia.ubicacion;
            document.getElementById('edit_estado').value = dependencia.estado;
        }
    </script>
</body>
</html>
