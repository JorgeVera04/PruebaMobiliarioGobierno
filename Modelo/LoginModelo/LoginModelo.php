<?php
class LoginModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function verificarUsuario($username, $password) {
        // Prepara la consulta para obtener el usuario por su nombre
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verifica la contraseña usando password_verify
        if ($user && password_verify($password, $user['password'])) {
            return $user;  // Usuario válido
        }
        
        return null; // Usuario o contraseña incorrectos
    }

    public function usuarioExiste($username) {
        $sql = "SELECT id FROM usuarios WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Retorna true si el usuario ya existe
    }
    
    public function guardarUsuario($username, $passwordHash, $rol) {
        $sql = "INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conn->error);
        }
        $stmt->bind_param("sss", $username, $passwordHash, $rol);
        
        // Ejecuta la consulta y verifica si fue exitosa
        if ($stmt->execute()) {
            return true;
        } else {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
    }
    
    }
?>
