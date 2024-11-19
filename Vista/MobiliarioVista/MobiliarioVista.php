
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Mobiliarios Asignados a Empleados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Vista/styles.css">
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
                        <option value="<?= $categoria['categoria'] ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $categoria['categoria'] ? 'selected' : '' ?>>
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
        <thead>
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
