<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
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
    <div class="container my-5">
        <h2 class="text-center mb-4">Registro de Usuario</h2>

        <!-- Mensaje de error en caso de que el registro falle -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=registro">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="clave_secreta" class="form-label">Clave Secreta (solo para personal autorizado)</label>
                <input type="password" class="form-control" id="clave_secreta" name="clave_secreta" required>
                <small class="form-text text-muted">Solo personal autorizado por el Gobierno de Oaxaca.</small>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
            <a href="index.php?action=login" class="btn btn-secondary">Volver al Inicio de Sesión</a>
        </form>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-auto">
        <div class="container">
            <p>Contacto: +521244235245 | Email: segob@secretaria.mx</p>
            <p>Responsables: Jorge Eli Vera Lazaro, Ismael Morales Diaz, Zianya Tayde Joffre Gonzalez, Julian Paz Cortes Arcos</p>
            <small>&copy; 2024 Gobierno de Oaxaca. Todos los derechos reservados.</small>
        </div>
    </footer>
</body>
</html>
