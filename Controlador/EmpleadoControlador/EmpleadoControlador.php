<?php
require_once 'Modelo/EmpleadoModelo/EmpleadoModelo.php';
require_once 'conexion.php';
require_once 'libs/fpdf186/fpdf.php';

class EmpleadoControlador {
    private $modelo;

    public function __construct($db) {
        $this->modelo = new EmpleadoModelo($db);
    }

    public function mostrarEmpleados($filtro = '') {
        return $this->modelo->obtenerEmpleados($filtro);
    }

    public function obtenerDependencias() {
        return $this->modelo->obtenerDependencias();
    }

    public function obtenerPuestos() {
        return $this->modelo->obtenerPuestos();
    }

    public function agregarEmpleado($nombre, $idPuesto, $idDependencia, $estado) {
        $this->modelo->insertarEmpleado($nombre, $idPuesto, $idDependencia, $estado);
    }

    public function editarEmpleado($id, $nombre, $idPuesto, $idDependencia, $estado) {
        $this->modelo->actualizarEmpleado($id, $nombre, $idPuesto, $idDependencia, $estado);
    }

    public function eliminarEmpleado($id) {
        $this->modelo->eliminarEmpleado($id);
    }

    public function cambiarEstadoEmpleado($idEmpleado, $estado) {
        $this->modelo->actualizarEstadoEmpleado($idEmpleado, $estado);
    }
    
    public function generarPDF($filtro = '') {
        // Obtener los empleados aplicando el filtro
        $result = $this->modelo->obtenerEmpleados($filtro);
    
        // Crear un nuevo PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Título del documento
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(200, 220, 255); // Color de fondo para el título
        $pdf->Cell(0, 12, utf8_decode('Listado de Empleados - Gobierno de Oaxaca'), 0, 1, 'C', true);
        $pdf->Ln(8);
    
        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(180, 180, 255); // Color para el encabezado de la tabla
        $pdf->Cell(50, 10, utf8_decode('Nombre'), 1, 0, 'C', true);
        $pdf->Cell(50, 10, utf8_decode('Puesto'), 1, 0, 'C', true);
        $pdf->Cell(50, 10, utf8_decode('Dependencia'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Estado', 1, 1, 'C', true);
    
        // Contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(240, 240, 255); // Color alternativo para las filas
        $fill = false;
    
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(50, 10, utf8_decode($row['nombre_empleado']), 1, 0, 'L', $fill);
            $pdf->Cell(50, 10, utf8_decode($row['nombre_puesto']), 1, 0, 'L', $fill);
            $pdf->Cell(50, 10, utf8_decode($row['nombre_dependencia']), 1, 0, 'L', $fill);
            $pdf->Cell(30, 10, $row['estado'], 1, 1, 'C', $fill);
            $fill = !$fill; // Alterna el color de las filas
        }
    
        // Salida del PDF
        $pdf->Output('I', 'Empleados_Gobierno_Oaxaca.pdf');
    }
}
?>
