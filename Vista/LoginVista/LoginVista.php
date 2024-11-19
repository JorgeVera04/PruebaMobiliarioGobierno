<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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

    <!-- Contenedor principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Iniciar Sesión</h2>
                    </div>
                    <div class="card-body">

                        <!-- Mostrar mensaje de éxito de registro si existe -->
                        <?php if (isset($_SESSION['successMessage'])): ?>
                            <div class="alert alert-success text-center">
                                <?= htmlspecialchars($_SESSION['successMessage']) ?>
                            </div>
                            <?php unset($_SESSION['successMessage']); // Eliminar el mensaje después de mostrarlo ?>
                        <?php endif; ?>

                        <!-- Mensaje de error en caso de fallo en el inicio de sesión -->
                        <?php if (isset($errorMessage)): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($errorMessage) ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?action=login" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
    <p>¿No tienes una cuenta? <a href="index.php?action=registro">Regístrate aquí</a></p>
</div>

    <!-- Footer -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>