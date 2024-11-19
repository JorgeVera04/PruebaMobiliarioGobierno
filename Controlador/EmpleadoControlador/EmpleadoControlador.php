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
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(33, 37, 41); // Texto negro
        $pdf->SetFillColor(200, 220, 255); // Fondo azul claro
        $pdf->Cell(0, 15, utf8_decode('Listado de Empleados - Gobierno de Oaxaca'), 0, 1, 'C', true);
        $pdf->Ln(10);
    
        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(52, 58, 64); // Fondo gris oscuro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(10, 12, utf8_decode('N°'), 1, 0, 'C', true);
        $pdf->Cell(50, 12, utf8_decode('Nombre'), 1, 0, 'C', true);
        $pdf->Cell(50, 12, utf8_decode('Puesto'), 1, 0, 'C', true);
        $pdf->Cell(50, 12, utf8_decode('Dependencia'), 1, 0, 'C', true);
        $pdf->Cell(30, 12, 'Estado', 1, 1, 'C', true);
    
        // Contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        $fillColor = [240, 240, 255]; // Color de fondo alternativo
        $normalColor = [255, 255, 255]; // Fondo blanco normal
        $contador = 1;
    
        while ($row = $result->fetch_assoc()) {
            // Alterna colores de fondo
            $background = ($contador % 2 == 0) ? $fillColor : $normalColor;
            $pdf->SetFillColor($background[0], $background[1], $background[2]);
    
            $pdf->Cell(10, 10, $contador, 1, 0, 'C', true); // Columna de contador
            $pdf->Cell(50, 10, $this->truncarTexto(utf8_decode($row['nombre_empleado']), 30), 1, 0, 'L', true);
            $pdf->Cell(50, 10, $this->truncarTexto(utf8_decode($row['nombre_puesto']), 20), 1, 0, 'L', true);
            $pdf->Cell(50, 10, $this->truncarTexto(utf8_decode($row['nombre_dependencia']), 25), 1, 0, 'L', true);
            $pdf->Cell(30, 10, utf8_decode($row['estado']), 1, 1, 'C', true);
            $contador++;
        }
    
        // Salida del PDF
        $pdf->Output('I', 'Empleados_Gobierno_Oaxaca.pdf');
    }
    
    // Método para truncar texto con "..." al final
    private function truncarTexto($texto, $maxLongitud) {
        return (strlen($texto) > $maxLongitud) ? substr($texto, 0, $maxLongitud) . '...' : $texto;
    }
    
}
?>
