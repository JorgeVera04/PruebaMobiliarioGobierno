<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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

<!-- Carrusel de Imágenes -->
<div id="dashboardCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        $imagenes = [
            "https://www.oaxaca.gob.mx/comunicacion/wp-content/uploads/sites/28/2023/10/WhatsApp-Image-2023-10-17-at-5.04.35-PM-1024x683.jpeg",
            "https://www.oaxaca.gob.mx/comunicacion/wp-content/uploads/sites/28/2021/10/Personal-con-mas-de-20-anos-de-servicio-4.jpg",
            "https://www.oaxaca.gob.mx/comunicacion/trabajadoras-y-trabajadores-del-gobierno-del-estado-pilar-fundamental-para-el-desarrollo-de-oaxaca-imm/dif-dia-del-empleado-04/"
        ];
        shuffle($imagenes);
        foreach ($imagenes as $index => $imagen) {
            echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                    <img src="' . $imagen . '" class="d-block w-100" alt="Imagen ' . ($index + 1) . '">
                  </div>';
        }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<!-- Opciones del Dashboard -->
<div class="container mt-5">
    <div class="row text-center">
        <div class="col-md-3">
            <a href="index.php?action=empleadosAdmin" class="text-decoration-none text-dark">
                <div class="card dashboard-card shadow-sm p-4">
                    <i class="bi bi-people-fill display-3"></i>
                    <h3 class="mt-3">Empleados (Admin)</h3>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="index.php?action=adminMobiliario" class="text-decoration-none text-dark">
                <div class="card dashboard-card shadow-sm p-4">
                    <i class="bi bi-gear-fill display-3"></i>
                    <h3 class="mt-3">Asignación de mobiliarios (Admin)</h3>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="index.php?action=dependenciasAdmin" class="text-decoration-none text-dark">
                <div class="card dashboard-card shadow-sm p-4">
                    <i class="bi bi-building display-3"></i>
                    <h3 class="mt-3">Dependencias (Admin)</h3>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="index.php?action=listarMobiliarios" class="text-decoration-none text-dark">
                <div class="card dashboard-card shadow-sm p-4">
                    <i class="bi bi-box-seam display-3"></i>
                    <h3 class="mt-3">Lista de Mobiliarios (Admin)</h3>
                </div>
            </a>
        </div>
    </div>
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
