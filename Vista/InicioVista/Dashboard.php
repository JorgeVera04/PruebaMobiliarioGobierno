<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Estilos para el tamaño del carrusel */
        #dashboardCarousel .carousel-inner img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        /* Estilos para el footer */
        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px 0;
            margin-top: 40px;
        }

        /* Estilo para mantener el footer en la parte inferior */
    body, html {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1;
    }

    footer {
        background-color: #343a40;
        color: white;
        padding: 20px 0;
        text-align: center;
        font-size: 0.9em;
        margin-top: auto;
    }

        </style>
    </head>
    <body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Cambia el enlace para que apunte a InicioVista.html -->
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=empleados">Empleados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=dependencias">Dependencias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=mobiliario">Mobiliario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Carrusel de Imágenes -->
    <div id="dashboardCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            // Lista de URLs de las imágenes de carrusel
            $imagenes = [
                "https://www.oaxaca.gob.mx/comunicacion/wp-content/uploads/sites/28/2023/10/WhatsApp-Image-2023-10-17-at-5.04.35-PM-1024x683.jpeg",
                "https://www.oaxaca.gob.mx/comunicacion/wp-content/uploads/sites/28/2021/10/Personal-con-mas-de-20-anos-de-servicio-4.jpg",
                "https://www.oaxaca.gob.mx/comunicacion/trabajadoras-y-trabajadores-del-gobierno-del-estado-pilar-fundamental-para-el-desarrollo-de-oaxaca-imm/dif-dia-del-empleado-04/"
            ];

            // Barajar las imágenes de forma aleatoria
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
        <div class="col-md-4">
            <a href="index.php?action=empleadosAdmin" class="text-decoration-none text-dark">
                <div class="card shadow-sm p-4">
                    <i class="bi bi-people-fill display-3"></i>
                    <h3 class="mt-3">Empleados (Admin)</h3>
                </div>
            </a>
        </div>

        <div class="col-md-4">
    <a href="index.php?action=adminMobiliario" class="text-decoration-none text-dark"> 
        <div class="card shadow-sm p-4">
            <i class="bi bi-gear-fill display-3"></i>
            <h3 class="mt-3">Administración de Mobiliarios</h3>
        </div>
    </a>
</div>



            <div class="col-md-3">
            <a href="index.php?action=dependenciasAdmin" class="text-decoration-none text-dark">
                <div class="card shadow-sm p-4">
                    <i class="bi bi-building display-3"></i>
                    <h3 class="mt-3">Dependencias (Admin)</h3>
                </div>
            </a>
        </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p>Contacto: +521244235245 | Email: segob@secretaria.mx</p>
            <p>Responsables: Jorge Eli Vera Lazaro, Ismael Morales Diaz, Zianya Tayde Joffre Gonzalez, Julian Paz Cortes Arcos</p>
            <small>&copy; 2024 Gobierno de Oaxaca. Todos los derechos reservados.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</body>
</html>
