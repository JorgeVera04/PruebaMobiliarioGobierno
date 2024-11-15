<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Asegura que el footer se mantenga en la parte inferior */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
        }
        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar con botón de inicio -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Cambia el enlace para que apunte a InicioVista.html -->
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php">Inicio</a>
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
    <footer class="text-center mt-auto">
        <div class="container">
            <p>Contacto: +521244235245 | Email: segob@secretaria.mx</p>
            <p>Responsables: Jorge Eli Vera Lazaro, Ismael Morales Diaz, Zianya Tayde Joffre Gonzalez, Julian Paz Cortes Arcos</p>
            <small>&copy; 2024 Gobierno de Oaxaca. Todos los derechos reservados.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
