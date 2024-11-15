<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dependencias - Gobierno de Oaxaca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://seeklogo.com/images/O/oaxaca-gobierno-del-estado-logo-D127C58E05-seeklogo.com.png" alt="Logo Oaxaca" width="50">
                Gobierno de Oaxaca - Dependencias
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=empleados">Empleados</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=mobiliario">Mobiliario</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Título y Formulario de Búsqueda -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Dependencias</h2>

        <!-- Formulario de Filtro por Ubicación o Estado -->
        <form method="GET" action="index.php" class="input-group mb-3">
            <input type="hidden" name="action" value="dependencias">
            <select name="filtro" class="form-select">
                <option value="">Selecciona una opción de filtrado</option>

                <!-- Opciones dinámicas de ubicaciones únicas -->
                <?php while ($row = $ubicaciones->fetch_assoc()): ?>
                    <option value="ubicacion:<?php echo $row['ubicacion']; ?>">
                        Ubicación: <?php echo htmlspecialchars($row['ubicacion']); ?>
                    </option>
                <?php endwhile; ?>

                <!-- Opciones de estado -->
                <?php foreach ($estados as $estado): ?>
                    <option value="estado:<?php echo $estado; ?>">
                        Estado: <?php echo htmlspecialchars($estado); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </form>

        <!-- Generar PDF -->
        <div class="text-end mb-3">
            <a href="index.php?action=generar_pdf_dependencias&filtro=<?= urlencode(isset($_GET['filtro']) ? $_GET['filtro'] : '') ?>" class="btn btn-danger">Generar PDF</a>
        </div>

        <!-- Resultados de la tabla -->
        <div id="resultado">
            <?php
            $contadorFila = 1; // Inicializa el contador de filas
            $totalDependencias = $result->num_rows; // Total de dependencias
            ?>

            <?php if ($totalDependencias > 0): ?>
                <table class="table table-bordered table-hover mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $contadorFila++; ?></td>
                                <td><?php echo htmlspecialchars($row["nombre_dependencia"]); ?></td>
                                <td><?php echo htmlspecialchars($row["ubicacion"]); ?></td>
                                <td><?php echo htmlspecialchars($row["estado"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Muestra el total de dependencias -->
                <p class="text-end"><strong>Total de dependencias: <?php echo $totalDependencias; ?></strong></p>
            <?php else: ?>
                <p class="text-center mt-3">No se encontraron resultados para la selección actual.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
