<?php 
class MobiliarioModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener el mobiliario asignado con filtros
    public function obtenerMobiliarioAsignado($id_empleado = null, $categoria = null) {
        $sql = "
            SELECT 
                e.nombre_empleado,
                m.descripcion AS mobiliario,
                m.categoria,
                m.estado,
                h.fecha_asignacion,
                h.fecha_desasignacion
            FROM historialasignacion h
            JOIN empleado e ON h.id_empleado = e.id_empleado
            JOIN mobiliario m ON h.id_mobiliario = m.id_mobiliario
            WHERE 1=1
        ";

        $params = [];
        $types = '';

        // Agregar filtro por id_empleado si está definido
        if ($id_empleado) {
            $sql .= " AND e.id_empleado = ?";
            $params[] = $id_empleado;
            $types .= 'i';
        }

        // Agregar filtro por categoria si está definido
        if ($categoria) {
            $sql .= " AND m.categoria = ?";
            $params[] = $categoria;
            $types .= 's';
        }

        $sql .= " ORDER BY h.fecha_asignacion DESC";

        $stmt = $this->conn->prepare($sql);

        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // Obtener lista de empleados
    public function obtenerEmpleados() {
        $sql = "SELECT id_empleado, nombre_empleado FROM empleado ORDER BY nombre_empleado";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener las categorías de mobiliarios
    public function obtenerCategorias() {
        $sql = "SELECT DISTINCT categoria FROM mobiliario";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener mobiliarios con los filtros
    public function obtenerMobiliarioFiltrado($id_empleado = null, $categoria = null) {
        return $this->obtenerMobiliarioAsignado($id_empleado, $categoria); // reutiliza la función anterior
    }

    // Insertar un nuevo mobiliario asignado
    public function insertarMobiliario($id_empleado, $mobiliario, $categoria, $estado) {
        $sql = "INSERT INTO historialasignacion (id_empleado, id_mobiliario, categoria, estado) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiss", $id_empleado, $mobiliario, $categoria, $estado);
        return $stmt->execute();
    }

    // Actualizar mobiliario asignado
    public function actualizarMobiliario($id_mobiliario, $id_empleado, $mobiliario, $categoria, $estado) {
        $sql = "UPDATE historialasignacion SET id_empleado = ?, id_mobiliario = ?, categoria = ?, estado = ? WHERE id_mobiliario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iissi", $id_empleado, $mobiliario, $categoria, $estado, $id_mobiliario);
        return $stmt->execute();
    }

    // Desasignar un mobiliario
    public function desasignarMobiliario($id_mobiliario) {
        $sql = "UPDATE historialasignacion SET fecha_desasignacion = NOW() WHERE id_mobiliario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_mobiliario);
        return $stmt->execute();
    }
}
?>
