<?php
require_once 'Modelo/MobiliarioModelo/MobiliarioModelo.php';
require_once 'conexion.php';
require_once 'libs/fpdf186/fpdf.php';

class MobiliarioControlador
{
    private $modelo;

    public function __construct($db)
    {
        $this->modelo = new MobiliarioModelo($db);
    }

    // Mostrar el listado de mobiliarios asignados a empleados
    public function mostrarMobiliarioAsignado($id_empleado = null, $categoria = null)
    {
        // Obtener los mobiliarios asignados con los filtros
        $resultados = $this->modelo->obtenerMobiliarioAsignado($id_empleado, $categoria);

        // Obtener empleados y categorías para los filtros
        $empleados = $this->modelo->obtenerEmpleados();
        $categorias = $this->modelo->obtenerCategorias();

        // Incluir la vista
        include 'Vista/MobiliarioVista/MobiliarioVista.php';
    }

    public function mostrarAdminMobiliario($id_empleado = null, $categoria = null)
    {
        // Obtener los mobiliarios asignados con los filtros
        $resultados = $this->modelo->obtenerMobiliarioAsignado($id_empleado, $categoria);

        // Obtener empleados y categorías para los filtros
        $empleados = $this->modelo->obtenerEmpleados();
        $categorias = $this->modelo->obtenerCategorias();

        // Incluir la vista
        include 'Vista/MobiliarioVista/AdminMobiliarioVista.php';
    }


    // Método para agregar un nuevo mobiliario asignado
    public function agregarMobiliario($id_empleado, $mobiliario, $categoria, $estado)
    {
        // Llamar al modelo para insertar el nuevo mobiliario
        $this->modelo->insertarMobiliario($id_empleado, $mobiliario, $categoria, $estado);
        // Redirigir a la vista de listado
        header("Location: index.php?action=mostrarMobiliarioAsignado");
    }

    // Método para editar un mobiliario
    public function editarMobiliario($id_mobiliario, $id_empleado, $mobiliario, $categoria, $estado)
    {
        // Llamar al modelo para actualizar los datos del mobiliario
        $this->modelo->actualizarMobiliario($id_mobiliario, $id_empleado, $mobiliario, $categoria, $estado);
        // Redirigir a la vista de listado
        header("Location: index.php?action=mostrarMobiliarioAsignado");
    }

    public function desasignarMobiliario($id_mobiliario)
    {
        $sql = "UPDATE historialasignacion SET fecha_desasignacion = NOW() WHERE id_mobiliario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_mobiliario);

        return $stmt->execute();  // Devuelve true si la ejecución es exitosa
    }

    // Generar un PDF con los mobiliarios asignados
// Generar un PDF con los mobiliarios asignados
    public function generarPDF($id_empleado = null, $categoria = null)
    {
        // Obtener los mobiliarios filtrados
        $resultados = $this->modelo->obtenerMobiliarioFiltrado($id_empleado, $categoria);



        // Crear el archivo PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Configuración de título y márgenes
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(33, 37, 41); // Color de texto en gris oscuro
        $pdf->Cell(0, 10, utf8_decode('Listado de Mobiliarios Asignados'), 0, 1, 'C');
        $pdf->Ln(5); // Espacio después del título

        // Ancho de la página (ajustado para dejar un margen de 10)
        $pageWidth = $pdf->GetPageWidth() - 20;

        // Calcular anchos proporcionales para las columnas
        $colWidths = [
            $pageWidth * 0.18, // Empleado
            $pageWidth * 0.18, // Mobiliario
            $pageWidth * 0.13, // Categoría
            $pageWidth * 0.13, // Estado
            $pageWidth * 0.19, // Fecha Asignación
            $pageWidth * 0.19  // Fecha Desasignación
        ];

        // Definir los encabezados de la tabla
        $pdf->SetFillColor(230, 230, 230); // Color de fondo gris claro para los encabezados
        $pdf->SetTextColor(0); // Color de texto en negro
        $pdf->SetDrawColor(200, 200, 200); // Color de borde en gris claro
        $pdf->SetFont('Arial', 'B', 10);

        // Encabezados de la tabla con ancho calculado y alineación centrada
        $pdf->Cell($colWidths[0], 10, utf8_decode('Empleado'), 1, 0, 'C', true);
        $pdf->Cell($colWidths[1], 10, utf8_decode('Mobiliario'), 1, 0, 'C', true);
        $pdf->Cell($colWidths[2], 10, utf8_decode('Categoria'), 1, 0, 'C', true);
        $pdf->Cell($colWidths[3], 10, utf8_decode('Estado'), 1, 0, 'C', true);
        $pdf->Cell($colWidths[4], 10, utf8_decode('Fecha Asignación'), 1, 0, 'C', true);
        $pdf->Cell($colWidths[5], 10, utf8_decode('Fecha Desasignación'), 1, 1, 'C', true); // Última celda de encabezado

        // Agregar los datos de los mobiliarios
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetFillColor(245, 245, 245); // Color de fondo para filas alternas
        $fill = false; // Variable para alternar color de fondo

        while ($row = $resultados->fetch_assoc()) {
            // Agregar cada celda con el ancho proporcional
            $pdf->Cell($colWidths[0], 10, utf8_decode($row['nombre_empleado']), 1, 0, 'C', $fill);
            $pdf->Cell($colWidths[1], 10, utf8_decode($row['mobiliario']), 1, 0, 'C', $fill);
            $pdf->Cell($colWidths[2], 10, utf8_decode($row['categoria']), 1, 0, 'C', $fill);
            $pdf->Cell($colWidths[3], 10, utf8_decode($row['estado']), 1, 0, 'C', $fill);
            $pdf->Cell($colWidths[4], 10, utf8_decode($row['fecha_asignacion']), 1, 0, 'C', $fill);
            $pdf->Cell($colWidths[5], 10, utf8_decode($row['fecha_desasignacion'] ? $row['fecha_desasignacion'] : 'No desasignado'), 1, 1, 'C', $fill);

            // Alternar color de fondo para filas
            $fill = !$fill;
        }

        // Salida del archivo PDF
        $pdf->Output('I', 'Mobiliarios_Asignados.pdf');
    }



}
?>