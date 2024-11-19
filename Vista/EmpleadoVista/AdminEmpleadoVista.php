<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Empleados - Gobierno de Oaxaca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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

    <!-- Mostrar mensaje de confirmación -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= $_SESSION['mensaje'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensaje']); // Limpiar el mensaje ?>
    <?php endif; ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">Administración de Empleados</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Agregar Nuevo Empleado</button>
        <a href="index.php?action=generar_pdf_empleados_admin&filtro=<?= isset($_GET['filtro']) ? urlencode($_GET['filtro']) : '' ?>" class="btn btn-danger mb-3">Generar PDF</a>

        <!-- Filtro -->
        <form method="GET" action="index.php" class="input-group mb-3">
            <input type="hidden" name="action" value="empleadosAdmin">
            <select name="filtro" class="form-select">
                <option value="">Selecciona una opción de filtrado</option>
                <?php if ($dependencias): ?>
                    <?php foreach ($dependencias as $dep): ?>
                        <option value="dependencia:<?= $dep['id_dependencia'] ?>"><?= $dep['nombre_dependencia'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($puestos): ?>
                    <?php foreach ($puestos as $puesto): ?>
                        <option value="puesto:<?= $puesto['id_puesto'] ?>"><?= $puesto['nombre_puesto'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
                <option value="estado:Activo">Estado: Activo</option>
                <option value="estado:Inactivo">Estado: Inactivo</option>
            </select>
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </form>

        <!-- Tabla de empleados -->
        <div id="resultado">
            <?php if ($result && $result->num_rows > 0): ?>
                <table class="table table-bordered table-hover mt-3">
                    <thead>
                        <tr><th>Nombre</th><th>Puesto</th><th>Dependencia</th><th>Estado</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["nombre_empleado"]) ?></td>
                                <td><?= htmlspecialchars($row["nombre_puesto"]) ?></td>
                                <td><?= htmlspecialchars($row["nombre_dependencia"]) ?></td>
                                <td><?= htmlspecialchars($row["estado"]) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" onclick="editEmployee(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">Editar</button>
                                    <a href="index.php?action=eliminarEmpleado&id=<?= urlencode($row['id_empleado']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                    <a href="index.php?action=cambiarEstadoEmpleado&id=<?= urlencode($row['id_empleado']) ?>&estado=<?= $row['estado'] === 'Activo' ? 'Inactivo' : 'Activo' ?>" class="btn btn-secondary btn-sm">
        <?= $row['estado'] === 'Activo' ? 'Desactivar' : 'Activar' ?>
                                </td>
                                
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center mt-3">No se encontraron resultados para la selección actual.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para Agregar Empleado -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Agregar Nuevo Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?action=agregarEmpleado" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_puesto" class="form-label">Puesto</label>
                            <select class="form-select" id="id_puesto" name="id_puesto" required>
                                <?php foreach ($puestos as $puesto): ?>
                                    <option value="<?= $puesto['id_puesto'] ?>"><?= htmlspecialchars($puesto['nombre_puesto']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_dependencia" class="form-label">Dependencia</label>
                            <select class="form-select" id="id_dependencia" name="id_dependencia" required>
                                <?php foreach ($dependencias as $dependencia): ?>
                                    <option value="<?= $dependencia['id_dependencia'] ?>"><?= htmlspecialchars($dependencia['nombre_dependencia']) ?></option>
                                <?php endforeach; ?>
                            </select>
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

    <!-- Modal para Editar Empleado -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Editar Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm" action="index.php?action=editarEmpleado" method="POST">
                        <input type="hidden" id="edit_id_empleado" name="id_empleado">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_puesto" class="form-label">Puesto</label>
                            <select class="form-select" id="edit_id_puesto" name="id_puesto" required>
                                <?php foreach ($puestos as $puesto): ?>
                                    <option value="<?= $puesto['id_puesto'] ?>"><?= htmlspecialchars($puesto['nombre_puesto']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_dependencia" class="form-label">Dependencia</label>
                            <select class="form-select" id="edit_id_dependencia" name="id_dependencia" required>
                                <?php foreach ($dependencias as $dependencia): ?>
                                    <option value="<?= $dependencia['id_dependencia'] ?>"><?= htmlspecialchars($dependencia['nombre_dependencia']) ?></option>
                                <?php endforeach; ?>
                            </select>
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
        // Función para cargar los datos del empleado en el formulario de edición
        function editEmployee(employee) {
            document.getElementById('edit_id_empleado').value = employee.id_empleado;
            document.getElementById('edit_nombre').value = employee.nombre_empleado;
            document.getElementById('edit_id_puesto').value = employee.id_puesto;
            document.getElementById('edit_id_dependencia').value = employee.id_dependencia;
            document.getElementById('edit_estado').value = employee.estado;
        }
    </script>

     <!-- Pie de página -->
     <footer class="bg-light">
        <!-- Sección superior con fondo burdeos y decorado -->
        <div class="bg-burdeo py-3">
                <a href="#" class="text-burdeo me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-burdeo"><i class="bi bi-twitter"></i></a>
            
        </div>
        
        <!-- Sección de contenido principal -->
        <div class="container py-4">
            <div class="row text-center text-md-start">
                <!-- Columna de contacto -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <img src="https://seeklogo.com/images/O/oaxaca-gobierno-del-estado-logo-D127C58E05-seeklogo.com.png" alt="Logo Oaxaca" width="120" class="mb-3">
                    <h5 class="fw-bold text-burdeo">CONTACTO</h5>
                    <p class="small mb-1">Ciudad Administrativa, Edificio, Nivel, Carretera Oaxaca-Istmo Km. 11.5 Tlalixtac de Cabrera Oaxaca.</p>
                    <p class="small mb-1"><i class="bi bi-telephone-fill me-2"></i>(951) 5015000, CP: 68270.</p>
                    <p class="small"><i class="bi bi-globe me-2"></i><a href="https://www.oaxaca.gob.mx" class="text-decoration-none text-burdeo">www.oaxaca.gob.mx</a></p>
                    <div>
                        <a href="#" class="text-burdeo me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-burdeo"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
                <!-- Columna de atención ciudadana -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold text-burdeo">ATENCIÓN CIUDADANA</h5>
                    <p class="small mb-1">Ciudad Administrativa</p>
                    <p class="small mb-1">Tel. (951) 5015000 Ext. 11408 y 11411</p>
                    <p class="small mb-1">Palacio de Gobierno</p>
                    <p class="small">Tel. (951) 5018100 Ext. 40033</p>
                    <br><br><br>
                    <p class="small mb-1">CREADO POR:</p>
                    <p class="small mb-1">Jorge Eli Vera Lazaro</p>
                    <p class="small">Ismael Morales Diaz</p>
                    <p class="small">Zianya Tayde Joffre Gonzales</p>
                </div>

                
                <!-- Columna de emergencias -->
                <div class="col-md-4">
                    <img src="https://www.oaxaca.gob.mx/comunicacion/wp-content/uploads/sites/28/2021/11/089-1.png" alt="089 Denuncia Anónima" class="emergency-banner mb-3 d-block mx-auto">
                    <img src="https://www.oaxaca.gob.mx/wp-content/themes/temaoax2024/assets/images/911.png" alt="911 Emergencias" class="d-block mx-auto">
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
