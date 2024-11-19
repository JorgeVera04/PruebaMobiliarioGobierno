<?php
class MobiliarioModelo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener el mobiliario asignado con filtros
    public function obtenerMobiliarioAsignado($id_empleado = null, $categoria = null, $estado = null) {
        $sql = "
            SELECT 
                h.id_historial,
                h.id_empleado,
                e.nombre_empleado,
                h.id_mobiliario,
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
    
        // Filtro por empleado
        if ($id_empleado) {
            $sql .= " AND h.id_empleado = ?";
            $params[] = $id_empleado;
            $types .= 'i';
        }
    
        // Filtro por categoría
        if ($categoria) {
            $sql .= " AND m.categoria = ?";
            $params[] = $categoria;
            $types .= 's';
        }
    
        // Filtro por estado (asignado o desasignado)
        if ($estado === 'asignado') {
            $sql .= " AND h.fecha_desasignacion IS NULL";
        } elseif ($estado === 'desasignado') {
            $sql .= " AND h.fecha_desasignacion IS NOT NULL";
        }
    
        $sql .= " ORDER BY h.fecha_asignacion DESC";
    
        $stmt = $this->conn->prepare($sql);
    
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function asignarMobiliario($id_empleado, $id_mobiliario)
{
    $sql = "INSERT INTO historialasignacion (id_empleado, id_mobiliario, fecha_asignacion) VALUES (?, ?, NOW())";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $id_empleado, $id_mobiliario);
    return $stmt->execute();
}

public function actualizarEstadoMobiliario($id_mobiliario, $nuevo_estado) {
    $sql = "UPDATE mobiliario SET estado = ? WHERE id_mobiliario = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $id_mobiliario);
    return $stmt->execute();
}




    // Obtener lista de empleados
    public function obtenerEmpleados() {
    $sql = "SELECT e.id_empleado, e.nombre_empleado, d.nombre_dependencia
            FROM empleado e
            JOIN dependencia d ON e.id_dependencia = d.id_dependencia";
    $result = $this->conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


    // Obtener las categorías de mobiliarios
    public function obtenerCategorias()
    {
        $sql = "SELECT DISTINCT categoria FROM mobiliario";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    
    

    // Obtener mobiliarios con los filtros
    public function obtenerMobiliarioFiltrado($id_empleado = null, $categoria = null, $estado = null)
    {
        return $this->obtenerMobiliarioAsignado($id_empleado, $categoria, $estado); // reutiliza la función anterior
    }


    // Desasignar un mobiliario
    public function desasignarMobiliario($id_mobiliario)
    {
        $sql = "UPDATE historialasignacion SET fecha_desasignacion = NOW() WHERE id_mobiliario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_mobiliario);
        return $stmt->execute();
    }

    

    public function insertarMobiliario($descripcion, $categoria, $estado, $id_dependencia)
{
    $sql = "INSERT INTO mobiliario (descripcion, categoria, estado, id_dependencia) VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssi", $descripcion, $categoria, $estado, $id_dependencia);
    return $stmt->execute();
}

public function eliminarMobiliario($id_mobiliario) {
    $sql = "DELETE FROM mobiliario WHERE id_mobiliario = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_mobiliario);
    return $stmt->execute();
}



public function obtenerDependencias() {
    $sql = "SELECT id_dependencia, nombre_dependencia FROM dependencia";
    return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

public function actualizarAsignacion($id_historial, $id_empleado, $id_mobiliario) {
    $sql = "UPDATE historialasignacion 
            SET id_empleado = ?, id_mobiliario = ? 
            WHERE id_historial = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $id_empleado, $id_mobiliario, $id_historial);
    return $stmt->execute();
}



public function obtenerMobiliarios() {
    $sql = "SELECT m.id_mobiliario, m.descripcion, m.categoria, m.estado, d.nombre_dependencia
            FROM mobiliario m
            JOIN dependencia d ON m.id_dependencia = d.id_dependencia";
    $result = $this->conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}



public function obtenerTodosMobiliarios($categoria = null, $estado = null) {
    $sql = "
        SELECT 
            m.id_mobiliario,
            m.descripcion,
            m.categoria,
            m.estado,
            d.nombre_dependencia
        FROM mobiliario m
        LEFT JOIN dependencia d ON m.id_dependencia = d.id_dependencia
        WHERE 1=1
    ";

    $params = [];
    $types = '';

    // Filtro por categoría
    if ($categoria) {
        $sql .= " AND m.categoria = ?";
        $params[] = $categoria;
        $types .= 's';
    }

    // Filtro por estado
    if ($estado) {
        $sql .= " AND m.estado = ?";
        $params[] = $estado;
        $types .= 's';
    }

    $stmt = $this->conn->prepare($sql);

    if ($types) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result();
}

public function obtenerDependenciaEmpleado($id_empleado) {
    $sql = "SELECT d.nombre_dependencia 
            FROM empleado e 
            JOIN dependencia d ON e.id_dependencia = d.id_dependencia 
            WHERE e.id_empleado = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['nombre_dependencia'] ?? null;
}

public function obtenerDependenciaMobiliario($id_mobiliario) {
    $sql = "SELECT d.nombre_dependencia 
            FROM mobiliario m 
            JOIN dependencia d ON m.id_dependencia = d.id_dependencia 
            WHERE m.id_mobiliario = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_mobiliario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['nombre_dependencia'] ?? null;
}




}
?>