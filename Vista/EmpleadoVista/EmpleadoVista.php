<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - Gobierno de Oaxaca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://seeklogo.com/images/O/oaxaca-gobierno-del-estado-logo-D127C58E05-seeklogo.com.png" alt="Logo Oaxaca" width="50">
                Gobierno de Oaxaca - Empleados
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=dependencias">Dependencias</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=mobiliario">Mobiliario</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Título y Formulario de Búsqueda -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Empleados</h2>

        <!-- Formulario de Filtro por Dependencia, Puesto o Estado -->
        <form method="GET" action="index.php" class="input-group mb-3">
            <input type="hidden" name="action" value="empleados">
            <select name="filtro" class="form-select">
                <option value="">Selecciona una opción de filtrado</option>

                <!-- Dependencias dinámicas -->
                <?php if ($dependencias->num_rows > 0): ?>
                    <?php while($row = $dependencias->fetch_assoc()): ?>
                        <option value="dependencia:<?php echo $row['id_dependencia']; ?>">
                            Dependencia: <?php echo htmlspecialchars($row['nombre_dependencia']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>

                <!-- Puestos dinámicos -->
                <?php if ($puestos->num_rows > 0): ?>
                    <?php while($row = $puestos->fetch_assoc()): ?>
                        <option value="puesto:<?php echo $row['id_puesto']; ?>">
                            Puesto: <?php echo htmlspecialchars($row['nombre_puesto']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>

                <!-- Opciones de estado -->
                <option value="estado:Activo">Estado: Activo</option>
                <option value="estado:Inactivo">Estado: Inactivo</option>
            </select>
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </form>

        <!-- Botón para Generar PDF -->
        <a href="index.php?action=generar_pdf_empleados<?php echo isset($_GET['filtro']) ? '&filtro=' . urlencode($_GET['filtro']) : ''; ?>" class="btn btn-danger mb-3">Generar PDF</a>

        <!-- Contenedor para la tabla de resultados -->
        <div id="resultado">
            <?php
            // Inicializa el contador de filas
            $contadorFila = 1;
            $totalEmpleados = $result->num_rows; // Cuenta total de empleados
            ?>

            <?php if ($totalEmpleados > 0): ?>
                <table class="table table-bordered table-hover mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Puesto</th>
                            <th>Dependencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $contadorFila++; ?></td>
                                <td><?php echo htmlspecialchars($row["nombre_empleado"]); ?></td>
                                <td><?php echo htmlspecialchars($row["nombre_puesto"]); ?></td>
                                <td><?php echo htmlspecialchars($row["nombre_dependencia"]); ?></td>
                                <td><?php echo htmlspecialchars($row["estado"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Muestra el total de empleados -->
                <p class="text-end"><strong>Total de empleados: <?php echo $totalEmpleados; ?></strong></p>
            <?php else: ?>
                <p class="text-center mt-3">No se encontraron resultados para la selección actual.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
