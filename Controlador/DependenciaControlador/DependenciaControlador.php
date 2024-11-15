<?php
require_once 'Modelo/DependenciaModelo/DependenciaModelo.php';
require_once 'conexion.php';
require_once 'libs/fpdf186/fpdf.php';

class DependenciaControlador {
    private $modelo;
    private $conn;

    // Constructor para inicializar el modelo y la conexión
    public function __construct($db) {
        $this->modelo = new DependenciaModelo($db);
        $this->conn = $db;
    }

    // Método para mostrar dependencias con filtro
    public function mostrarDependencias() {
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
        $result = $this->modelo->obtenerDependencias($filtro);

        // Obtener todas las ubicaciones y estados para filtros
        $ubicaciones = $this->obtenerUbicaciones();
        $estados = ['Activo', 'Inactivo'];

        return compact('result', 'ubicaciones', 'estados');
    }

    // Obtener ubicaciones únicas
    public function obtenerUbicaciones() {
        return $this->modelo->obtenerUbicaciones();
    }

    // Métodos CRUD para dependencias
    public function agregarDependencia($nombre, $ubicacion, $estado) {
        return $this->modelo->insertarDependencia($nombre, $ubicacion, $estado);
    }

    public function editarDependencia($id, $nombre, $ubicacion, $estado) {
        return $this->modelo->actualizarDependencia($id, $nombre, $ubicacion, $estado);
    }

    public function eliminarDependencia($id) {
        return $this->modelo->eliminarDependencia($id);
    }


    public function obtenerDependencias($filtro = '') {
    $sql = "SELECT id_dependencia, nombre_dependencia, ubicacion, estado FROM dependencia";
    
    // Verifica si el filtro es de estado o de ubicación
    if (!empty($filtro)) {
        if (strpos($filtro, 'ubicacion:') === 0) {
            $ubicacion = str_replace('ubicacion:', '', $filtro);
            $sql .= " WHERE ubicacion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $ubicacion);
        } elseif (strpos($filtro, 'estado:') === 0) {
            $estado = str_replace('estado:', '', $filtro);
            $sql .= " WHERE estado = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $estado);
        } else {
            // Si el filtro no es reconocible, no se aplica
            $stmt = $this->conn->prepare($sql);
        }
    } else {
        $stmt = $this->conn->prepare($sql);
    }
    
    // Siempre aplicamos el orden alfabético
    $sql .= " ORDER BY nombre_dependencia ASC";
    $stmt->execute();
    
    return $stmt->get_result();
}

    
    public function generarPDF($filtro = '') {
        // Obtener las dependencias aplicando el filtro
        $result = $this->modelo->obtenerDependencias($filtro);
    
        // Crear un nuevo PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Título del documento
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(200, 220, 255); // Color de fondo para el título
        $pdf->Cell(0, 12, utf8_decode('Listado de Dependencias - Gobierno de Oaxaca'), 0, 1, 'C', true);
        $pdf->Ln(8);
    
        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(180, 180, 255); // Color para el encabezado de la tabla
    
        // Ajuste de ancho para cada columna (usando toda la anchura de la página)
        $pdf->Cell(63, 10, utf8_decode('Dependencia'), 1, 0, 'C', true);
        $pdf->Cell(63, 10, utf8_decode('Ubicación'), 1, 0, 'C', true);
        $pdf->Cell(63, 10, 'Estado', 1, 1, 'C', true);
    
        // Contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(240, 240, 255); // Color alternativo para las filas
        $fill = false;
    
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(63, 10, utf8_decode($this->shortenText($row['nombre_dependencia'], 30)), 1, 0, 'L', $fill);
            $pdf->Cell(63, 10, utf8_decode($this->shortenText($row['ubicacion'], 30)), 1, 0, 'L', $fill);
            $pdf->Cell(63, 10, $row['estado'], 1, 1, 'C', $fill);
            $fill = !$fill; // Alterna el color de las filas
        }
    
        // Salida del PDF
        $pdf->Output('I', 'Dependencias_Gobierno_Oaxaca.pdf');
    }
    

// Función auxiliar para acortar el texto si es demasiado largo
private function shortenText($text, $maxLength) {
    return (strlen($text) > $maxLength) ? substr($text, 0, $maxLength) . '...' : $text;
}

}
?>
