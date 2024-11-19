<?php
require_once 'Modelo/MobiliarioModelo/MobiliarioModelo.php';
require_once 'conexion.php';
require_once 'libs/fpdf186/fpdf.php';

class MobiliarioControlador
{
    private $modelo;
    private $conn;

    public function __construct($db)
    {
        $this->modelo = new MobiliarioModelo($db);
        $this->conn = $db; // Asigna la conexión al controlador
    }

    // Mostrar el listado de mobiliarios asignados a empleados
    public function mostrarMobiliarioAsignado($id_empleado = null, $categoria = null, $estado = null)
    {
        // Obtener los mobiliarios asignados con los filtros
        $resultados = $this->modelo->obtenerMobiliarioAsignado($id_empleado, $categoria, $estado);

        // Obtener empleados y categorías para los filtros
        $empleados = $this->modelo->obtenerEmpleados();
        $categorias = $this->modelo->obtenerCategorias();
        

        // Incluir la vista
        include 'Vista/MobiliarioVista/MobiliarioVista.php';
    }

    public function mostrarAdminMobiliario($id_empleado = null, $categoria = null, $estado = null) {
        // Obtener los datos filtrados según los parámetros
        $resultados = $this->modelo->obtenerMobiliarioAsignado($id_empleado, $categoria, $estado);
        
        // Obtener listas para los filtros
        $empleados = $this->modelo->obtenerEmpleados();
        $categorias = $this->modelo->obtenerCategorias();
        $mobiliarios = $this->modelo->obtenerMobiliarios();
        $dependencias = $this->modelo->obtenerDependencias(); // Aquí corriges el nombre a plural
    
        // Cargar la vista con los datos
        include 'Vista/MobiliarioVista/AdminMobiliarioVista.php';
    }
    

public function eliminarMobiliario($id_mobiliario) {
    try {
        $resultado = $this->modelo->eliminarMobiliario($id_mobiliario);
        $_SESSION['mensaje'] = 'Mobiliario eliminado correctamente.';
        $_SESSION['tipo_mensaje'] = 'success';
    } catch (Exception $e) {
        $_SESSION['mensaje'] = 'No se puede eliminar el mobiliario porque tiene asignaciones relacionadas.';
        $_SESSION['tipo_mensaje'] = 'danger';
    }

    // Redirigir a la vista de administración
    header("Location: index.php?action=adminMobiliario");
    exit();
}




    // Método para agregar un nuevo mobiliario asignado

    public function agregarMobiliario($descripcion, $categoria, $estado, $id_dependencia)
{
    // Llamar al modelo para insertar el mobiliario
    $this->modelo->insertarMobiliario($descripcion, $categoria, $estado, $id_dependencia);
    // Redirigir a la vista de listado
    header("Location: index.php?action=mostrarAdminMobiliario");
}



public function desasignarMobiliario($id_mobiliario)
{
    $resultado = $this->modelo->desasignarMobiliario($id_mobiliario);
    return $resultado; // Regresa el resultado de la operación
}



    // Generar un PDF con los mobiliarios asignados
    public function generarPDF($id_empleado = null, $categoria = null, $estado = null) {
        // Obtener los mobiliarios filtrados
        $resultados = $this->modelo->obtenerMobiliarioAsignado($id_empleado, $categoria, $estado);
    
        // Crear el archivo PDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
    
        // Configuración de título y márgenes
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetFillColor(200, 220, 255); // Color del fondo del título
        $pdf->Cell(0, 12, utf8_decode('Listado de Mobiliarios Asignados'), 0, 1, 'C', true);
        $pdf->Ln(8);
    
        // Anchura total de la página menos márgenes
        $pageWidth = $pdf->GetPageWidth() - 20;
    
        // Anchuras relativas de las columnas
        $columnWidths = [
            0.06 * $pageWidth, // N°
            0.18 * $pageWidth, // Empleado
            0.18 * $pageWidth, // Mobiliario
            0.15 * $pageWidth, // Categoria
            0.15 * $pageWidth, // Estado
            0.14 * $pageWidth, // Fecha Asignacion
            0.14 * $pageWidth  // Fecha Desasignacion
        ];
    
        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(52, 58, 64); // Fondo gris oscuro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
    
        $headers = ['N°', 'Empleado', 'Mobiliario', 'Categoria', 'Estado', 'Fecha Asign.', 'Fecha Desasg.'];
        foreach ($headers as $key => $header) {
            $pdf->Cell($columnWidths[$key], 10, utf8_decode($header), 1, 0, 'C', true);
        }
        $pdf->Ln();
    
        // Contenido de la tabla
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        $fillColor = [240, 240, 255]; // Color de fondo alternativo
        $normalColor = [255, 255, 255]; // Fondo blanco normal
        $contador = 1;
    
        while ($row = $resultados->fetch_assoc()) {
            // Alternar colores de fondo
            $background = ($contador % 2 == 0) ? $fillColor : $normalColor;
            $pdf->SetFillColor($background[0], $background[1], $background[2]);
    
            $pdf->Cell($columnWidths[0], 10, $contador, 1, 0, 'C', true);
            $pdf->Cell($columnWidths[1], 10, utf8_decode($this->shortenText($row['nombre_empleado'], 20)), 1, 0, 'L', true);
            $pdf->Cell($columnWidths[2], 10, utf8_decode($this->shortenText($row['mobiliario'], 20)), 1, 0, 'L', true);
            $pdf->Cell($columnWidths[3], 10, utf8_decode($this->shortenText($row['categoria'], 15)), 1, 0, 'L', true);
            $pdf->Cell($columnWidths[4], 10, utf8_decode($row['estado']), 1, 0, 'C', true);
            $pdf->Cell($columnWidths[5], 10, utf8_decode($row['fecha_asignacion']), 1, 0, 'C', true);
            $pdf->Cell($columnWidths[6], 10, utf8_decode($row['fecha_desasignacion'] ?? 'No desasignado'), 1, 1, 'C', true);
            $contador++;
        }
    
        // Si no hay resultados
        if ($contador === 1) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 15, 'No se encontraron registros.', 0, 1, 'C');
        }
    
        // Salida del archivo PDF
        $pdf->Output('I', 'Mobiliarios_Asignados.pdf');
    }
    
    // Función auxiliar para acortar el texto si es demasiado largo
    private function shortenText($text, $maxLength) {
        return (strlen($text) > $maxLength) ? substr($text, 0, $maxLength) . '...' : $text;
    }
    
    

    public function editarAsignacion($id_historial, $id_empleado, $id_mobiliario) {
    // Obtener la dependencia del empleado
    $dependenciaEmpleado = $this->modelo->obtenerDependenciaEmpleado($id_empleado);
    // Obtener la dependencia del mobiliario
    $dependenciaMobiliario = $this->modelo->obtenerDependenciaMobiliario($id_mobiliario);

    // Verificar si ambas dependencias fueron obtenidas
    if (is_null($dependenciaEmpleado) || is_null($dependenciaMobiliario)) {
        $_SESSION['mensaje'] = 'Error al verificar las dependencias. Verifica que los datos sean correctos.';
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location: index.php?action=adminMobiliario");
        exit();
    }

    // Verificar si las dependencias coinciden
    if (trim($dependenciaEmpleado) !== trim($dependenciaMobiliario)) {
        $_SESSION['mensaje'] = 'No se puede cambiar la asignación. Las dependencias no coinciden.';
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location: index.php?action=adminMobiliario");
        exit();
    }

    // Realizar la actualización de la asignación si las dependencias coinciden
    if ($this->modelo->actualizarAsignacion($id_historial, $id_empleado, $id_mobiliario)) {
        $_SESSION['mensaje'] = 'La asignación se actualizó correctamente.';
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = 'Hubo un error al actualizar la asignación.';
        $_SESSION['tipo_mensaje'] = 'danger';
    }

    header("Location: index.php?action=adminMobiliario");
    exit();
}

    

public function asignarMobiliario($id_empleado, $id_mobiliario) {
    // Obtener la dependencia del empleado
    $dependenciaEmpleado = $this->modelo->obtenerDependenciaEmpleado($id_empleado);
    // Obtener la dependencia del mobiliario
    $dependenciaMobiliario = $this->modelo->obtenerDependenciaMobiliario($id_mobiliario);

    // Verificar si ambas dependencias fueron obtenidas
    if (is_null($dependenciaEmpleado) || is_null($dependenciaMobiliario)) {
        $_SESSION['mensaje'] = 'Error al verificar las dependencias. Verifica que los datos sean correctos.';
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location: index.php?action=adminMobiliario");
        exit();
    }

    // Verificar si las dependencias coinciden
    if (trim($dependenciaEmpleado) !== trim($dependenciaMobiliario)) {
        $_SESSION['mensaje'] = 'No se puede asignar el mobiliario. Las dependencias no coinciden.';
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location: index.php?action=adminMobiliario");
        exit();
    }

    // Realizar la asignación si las dependencias coinciden
    if ($this->modelo->asignarMobiliario($id_empleado, $id_mobiliario)) {
        $_SESSION['mensaje'] = 'Mobiliario asignado correctamente.';
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = 'Hubo un error al asignar el mobiliario.';
        $_SESSION['tipo_mensaje'] = 'danger';
    }

    header("Location: index.php?action=adminMobiliario");
    exit();
}

    

    

    public function mostrarTodosMobiliarios($categoria = null, $estado = null) {
        // Reutiliza el modelo para obtener mobiliarios filtrados
        $resultados = $this->modelo->obtenerTodosMobiliarios($categoria, $estado);
        $categorias = $this->modelo->obtenerCategorias();
    
        // Cargar la vista para listar mobiliarios
        include 'Vista/MobiliarioVista/ListarMobiliariosVista.php';
    }
    
    public function generarPDFMobiliarios($categoria = null, $estado = null) {
        // Obtener los mobiliarios filtrados
        $resultados = $this->modelo->obtenerTodosMobiliarios($categoria, $estado);
    
        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Título del documento
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(33, 37, 41); // Color del texto (negro)
        $pdf->SetFillColor(245, 245, 245); // Fondo gris claro
        $pdf->Cell(0, 15, utf8_decode('Listado de Mobiliarios'), 0, 1, 'C', true);
        $pdf->Ln(10);
    
        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(52, 58, 64); // Fondo gris oscuro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
        $pdf->Cell(60, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, utf8_decode('Categoría'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, utf8_decode('Estado'), 1, 0, 'C', true);
        $pdf->Cell(50, 10, utf8_decode('Dependencia'), 1, 1, 'C', true);
    
        // Datos de la tabla
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        $contador = 1;
        while ($row = $resultados->fetch_assoc()) {
            $pdf->Cell(10, 10, $contador++, 1, 0, 'C');
            $pdf->Cell(60, 10, $this->truncarTexto(utf8_decode($row['descripcion']), 30), 1, 0, 'L');
            $pdf->Cell(40, 10, $this->truncarTexto(utf8_decode($row['categoria']), 20), 1, 0, 'L');
            $pdf->Cell(30, 10, utf8_decode($row['estado']), 1, 0, 'C');
            $pdf->Cell(50, 10, $this->truncarTexto(utf8_decode($row['nombre_dependencia']), 25), 1, 1, 'L');
        }
    
        // Salida del archivo PDF
        $pdf->Output('I', 'Mobiliarios.pdf');
    }
    
    // Método para truncar texto con "..." al final
    private function truncarTexto($texto, $maxLongitud) {
        return (strlen($texto) > $maxLongitud) ? substr($texto, 0, $maxLongitud) . '...' : $texto;
    }
    
    
    
    
}
?>