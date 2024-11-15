<?php
class EmpleadoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerEmpleados($filtro = '') {
        if ($filtro) {
            list($tipo, $valor) = explode(":", $filtro);

            if ($tipo === 'dependencia') {
                $sql = "SELECT e.id_empleado, e.nombre_empleado, p.nombre_puesto, d.nombre_dependencia, e.estado
                        FROM empleado e
                        JOIN puesto p ON e.id_puesto = p.id_puesto
                        JOIN dependencia d ON e.id_dependencia = d.id_dependencia
                        WHERE d.id_dependencia = ?
                        ORDER BY e.nombre_empleado ASC";

                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $valor);
            } elseif ($tipo === 'puesto') {
                $sql = "SELECT e.id_empleado, e.nombre_empleado, p.nombre_puesto, d.nombre_dependencia, e.estado
                        FROM empleado e
                        JOIN puesto p ON e.id_puesto = p.id_puesto
                        JOIN dependencia d ON e.id_dependencia = d.id_dependencia
                        WHERE p.id_puesto = ?
                        ORDER BY e.nombre_empleado ASC";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $valor);
            } elseif ($tipo === 'estado') {
                $sql = "SELECT e.id_empleado, e.nombre_empleado, p.nombre_puesto, d.nombre_dependencia, e.estado
                        FROM empleado e
                        JOIN puesto p ON e.id_puesto = p.id_puesto
                        JOIN dependencia d ON e.id_dependencia = d.id_dependencia
                        WHERE e.estado = ?
                        ORDER BY e.nombre_empleado ASC";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $valor);
            }
            $stmt->execute();
            return $stmt->get_result();
        } else {
            // Sin filtro
            $sql = "SELECT e.id_empleado, e.nombre_empleado, p.nombre_puesto, d.nombre_dependencia, e.estado
                    FROM empleado e
                    JOIN puesto p ON e.id_puesto = p.id_puesto
                    JOIN dependencia d ON e.id_dependencia = d.id_dependencia
                    ORDER BY e.nombre_empleado ASC"; 
            return $this->conn->query($sql);
        }
    }

    public function insertarEmpleado($nombre, $idPuesto, $idDependencia, $estado) {
        $sql = "INSERT INTO empleado (nombre_empleado, id_puesto, id_dependencia, estado) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siis", $nombre, $idPuesto, $idDependencia, $estado);
        return $stmt->execute();
    }

    public function actualizarEmpleado($idEmpleado, $nombre, $idPuesto, $idDependencia, $estado) {
        $sql = "UPDATE empleado SET nombre_empleado = ?, id_puesto = ?, id_dependencia = ?, estado = ? WHERE id_empleado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siisi", $nombre, $idPuesto, $idDependencia, $estado, $idEmpleado);
        return $stmt->execute();
    }

    public function eliminarEmpleado($idEmpleado) {
        // Primero eliminar registros relacionados en historialasignacion
        $sql = "DELETE FROM historialasignacion WHERE id_empleado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idEmpleado);
        $stmt->execute();
    
        // Luego elimina el empleado
        $sql = "DELETE FROM empleado WHERE id_empleado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idEmpleado);
        $stmt->execute();
    }
    
    public function actualizarEstadoEmpleado($idEmpleado, $estado) {
        $sql = "UPDATE empleado SET estado = ? WHERE id_empleado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $estado, $idEmpleado);
        $stmt->execute();
        $stmt->close();
    }
    

    public function obtenerDependencias() {
        $sql = "SELECT id_dependencia, nombre_dependencia FROM dependencia WHERE estado = 'Activo'";
        return $this->conn->query($sql);
    }

    public function obtenerPuestos() {
        $sql = "SELECT id_puesto, nombre_puesto FROM puesto WHERE estado = 'Activo'";
        return $this->conn->query($sql);
    }
}
?>
