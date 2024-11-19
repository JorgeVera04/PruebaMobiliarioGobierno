<?php
class DependenciaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
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
                $stmt = $this->conn->prepare($sql);
            }
        } else {
            $stmt = $this->conn->prepare($sql);
        }
    
        $sql .= " ORDER BY nombre_dependencia ASC"; // Orden alfabético
    
        $stmt->execute();
        return $stmt->get_result();
    }
    

    

    // Método para obtener ubicaciones únicas
    public function obtenerUbicaciones() {
        $sql = "SELECT DISTINCT ubicacion FROM dependencia WHERE estado = 'Activo' ORDER BY ubicacion ASC";
        return $this->conn->query($sql);
    }

    // Métodos CRUD básicos
    public function insertarDependencia($nombre, $ubicacion, $estado) {
        $sql = "INSERT INTO dependencia (nombre_dependencia, ubicacion, estado) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $ubicacion, $estado);
        return $stmt->execute();
    }

    public function actualizarDependencia($id, $nombre, $ubicacion, $estado) {
        $sql = "UPDATE dependencia SET nombre_dependencia = ?, ubicacion = ?, estado = ? WHERE id_dependencia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $ubicacion, $estado, $id);
        return $stmt->execute();
    }

    public function eliminarDependencia($id) {
        $sql = "DELETE FROM dependencia WHERE id_dependencia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
