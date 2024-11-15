<?php
// Antes de llamar a session_start(), verifica si no hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'C:/xampp/htdocs/mobiliario2/MVC/Modelo/LoginModelo/LoginModelo.php';
require_once 'C:/xampp/htdocs/mobiliario2/MVC/conexion.php';

class LoginControlador {
    private $modelo;

    public function __construct($db) {
        $this->modelo = new LoginModelo($db);
    }

    public function login($username, $password) {
        // Verificar usuario en el modelo
        $usuario = $this->modelo->verificarUsuario($username, $password);
        
        if ($usuario) {
            // Establecer sesión si el usuario es válido
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['rol'] = $usuario['rol'];
            
            // Redirige al dashboard
            header("Location: index.php?action=dashboard");
            exit();
        } else {
            // Si las credenciales son incorrectas, asignar mensaje de error
            $errorMessage = "Usuario o contraseña incorrectos.";
            include 'Vista/LoginVista/LoginVista.php';
        }
    }
    
    public function registrar($username, $password, $clave_secreta) {
        // Verificar si el usuario ya existe
        if ($this->modelo->usuarioExiste($username)) {
            $errorMessage = "El nombre de usuario ya está en uso. Por favor elige otro.";
            include 'Vista/LoginVista/RegistroVista.php';
            return; // Detener el proceso de registro
        }
    
        // Verificar si la clave secreta es correcta
        if ($clave_secreta !== 'goboax') {
            $errorMessage = "No puedes crear un usuario. Solo personal autorizado por el Gobierno de Oaxaca.";
            include 'Vista/LoginVista/RegistroVista.php';
            return; // Detener el proceso de registro
        }
    
        // Asignar el rol y encriptar la contraseña
        $rol = 'admin';
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
        // Guardar el nuevo usuario
        $resultado = $this->modelo->guardarUsuario($username, $passwordHash, $rol);
    
        if ($resultado) {
            $_SESSION['successMessage'] = "Usuario agregado exitosamente.";
            header("Location: index.php?action=login");
            exit();
        } else {
            $errorMessage = "Error al registrar el usuario. Intenta de nuevo.";
            include 'Vista/LoginVista/RegistroVista.php';
        }
    }
    
    
    
}


?>
