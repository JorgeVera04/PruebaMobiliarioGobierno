<?php
// Iniciar sesión para almacenar datos de sesión como el login de usuario
session_start();
define('BASE_URL', '/mobiliario2/MVC/');

// Incluye la conexión a la base de datos
require_once 'conexion.php';

// Verifica si se ha pasado alguna acción, si no, define una acción por defecto
$action = isset($_GET['action']) ? $_GET['action'] : 'inicio';

// Redirige al login si el usuario no ha iniciado sesión, excepto en acciones públicas
$accionesPublicas = ['login', 'registro'];
if (!in_array($action, $accionesPublicas) && !isset($_SESSION['username'])) {
    header("Location: index.php?action=login");
    exit();
}

// Enrutamiento según la acción solicitada
switch ($action) {

    case 'login':
        require_once 'Controlador/LoginControlador/LoginControlador.php';
        $loginController = new LoginControlador($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Si es un envío de formulario, llama a la función de login
            $username = $_POST['username'];
            $password = $_POST['password'];
            $loginController->login($username, $password);
        } else {
            // Muestra la vista de login
            include 'Vista/LoginVista/LoginVista.php';
        }
        break;

    case 'registro':
        require_once 'Controlador/LoginControlador/LoginControlador.php';
        $loginController = new LoginControlador($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtén los datos del formulario de registro
            $username = $_POST['username'];
            $password = $_POST['password'];
            $clave_secreta = $_POST['clave_secreta'];

            // Llama al método de registro del controlador
            $loginController->registrar($username, $password, $clave_secreta);
        } else {
            // Muestra la vista de registro
            include 'Vista/LoginVista/RegistroVista.php';
        }
        break;



    case 'dashboard':
        // Muestra el dashboard si el usuario está autenticado
        include 'Vista/InicioVista/dashboard.php';
        break;

    case 'empleados':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $empleadoController = new EmpleadoControlador($conn);

        // Obtiene el filtro si existe
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';

        // Obtiene los datos necesarios para la vista
        $result = $empleadoController->mostrarEmpleados($filtro);
        $dependencias = $empleadoController->obtenerDependencias();
        $puestos = $empleadoController->obtenerPuestos();

        // Incluye la vista de empleados
        include 'Vista/EmpleadoVista/EmpleadoVista.php';
        break;

    case 'empleadosAdmin':
        // Verificar si el usuario tiene rol de administrador
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
            $empleadoController = new EmpleadoControlador($conn);

            // Obtiene el filtro si existe
            $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';

            // Obtiene los datos necesarios para la vista
            $result = $empleadoController->mostrarEmpleados($filtro);
            $dependencias = $empleadoController->obtenerDependencias();
            $puestos = $empleadoController->obtenerPuestos();

            // Incluye la vista de administración de empleados
            include 'Vista/EmpleadoVista/AdminEmpleadoVista.php';
        } else {
            // Redirige al dashboard o al login si no es administrador
            header("Location: index.php?action=dashboard");
        }
        break;

    // Agregar Empleado
    case 'agregarEmpleado':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $controlador = new EmpleadoControlador($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $idPuesto = $_POST['id_puesto'];
            $idDependencia = $_POST['id_dependencia'];
            $estado = $_POST['estado'];
            $controlador->agregarEmpleado($nombre, $idPuesto, $idDependencia, $estado);

            // Mensaje de éxito
            $_SESSION['mensaje'] = "Empleado agregado exitosamente.";
            header("Location: index.php?action=empleadosAdmin");
            exit();
        }
        break;

    // Editar Empleado
    case 'editarEmpleado':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $controlador = new EmpleadoControlador($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpleado = $_POST['id_empleado'];
            $nombre = $_POST['nombre'];
            $idPuesto = $_POST['id_puesto'];
            $idDependencia = $_POST['id_dependencia'];
            $estado = $_POST['estado'];
            $controlador->editarEmpleado($idEmpleado, $nombre, $idPuesto, $idDependencia, $estado);

            // Mensaje de éxito
            $_SESSION['mensaje'] = "Empleado editado exitosamente.";
            header("Location: index.php?action=empleadosAdmin");
            exit();
        }
        break;

    // Eliminar Empleado
    case 'eliminarEmpleado':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $controlador = new EmpleadoControlador($conn);

        if (isset($_GET['id'])) {
            $idEmpleado = $_GET['id'];
            $controlador->eliminarEmpleado($idEmpleado);

            // Mensaje de éxito
            $_SESSION['mensaje'] = "Empleado eliminado exitosamente.";
            header("Location: index.php?action=empleadosAdmin");
            exit();
        }
        break;

    // Cambiar Estado del Empleado
    case 'cambiarEstadoEmpleado':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $controlador = new EmpleadoControlador($conn);

        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $idEmpleado = $_GET['id'];
            $estado = $_GET['estado'];
            $controlador->cambiarEstadoEmpleado($idEmpleado, $estado);

            // Mensaje de éxito
            $_SESSION['mensaje'] = "Estado del empleado actualizado exitosamente.";
            header("Location: index.php?action=empleadosAdmin");
            exit();
        }
        break;

    case 'generar_pdf_empleados':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $empleadoController = new EmpleadoControlador($conn);

        // Captura el filtro desde la URL
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';

        // Genera el PDF utilizando el filtro capturado
        $empleadoController->generarPDF($filtro);
        break;


    case 'generar_pdf_empleados_admin':
        require_once 'Controlador/EmpleadoControlador/EmpleadoControlador.php';
        $empleadoController = new EmpleadoControlador($conn);
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
        $empleadoController->generarPDF($filtro);
        break;


    case 'dependencias':
        require_once 'Controlador/DependenciaControlador/DependenciaControlador.php';
        $dependenciaController = new DependenciaControlador($conn);

        // Obtiene los datos necesarios para la vista
        $data = $dependenciaController->mostrarDependencias();

        // Extrae los datos para usarlos en la vista
        $result = $data['result'];
        $ubicaciones = $data['ubicaciones'];
        $estados = $data['estados'];

        // Incluye la vista de dependencias
        include 'Vista/DependenciaVista/DependenciaVista.php';
        break;


    // dep
    case 'dependenciasAdmin':
        require_once 'Controlador/DependenciaControlador/DependenciaControlador.php';
        $dependenciaController = new DependenciaControlador($conn);
        $data = $dependenciaController->mostrarDependencias();
        $result = $data['result']; // Dependencias a mostrar
        $ubicaciones = $data['ubicaciones']; // Ubicaciones únicas
        $estados = $data['estados']; // Posibles estados
        include 'Vista/DependenciaVista/AdminDependenciaVista.php';
        break;

    case 'agregarDependencia':
        require_once 'Controlador/DependenciaControlador/DependenciaControlador.php';
        $dependenciaController = new DependenciaControlador($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $ubicacion = $_POST['ubicacion'];
            $estado = $_POST['estado'];
            $dependenciaController->agregarDependencia($nombre, $ubicacion, $estado);
            header("Location: index.php?action=dependenciasAdmin&mensaje=Dependencia agregada correctamente");
            exit();
        }
        break;

    case 'editarDependencia':
        require_once 'Controlador/DependenciaControlador/DependenciaControlador.php';
        $dependenciaController = new DependenciaControlador($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_dependencia'];
            $nombre = $_POST['nombre'];
            $ubicacion = $_POST['ubicacion'];
            $estado = $_POST['estado'];
            $dependenciaController->editarDependencia($id, $nombre, $ubicacion, $estado);
            header("Location: index.php?action=dependenciasAdmin&mensaje=Dependencia actualizada correctamente");
            exit();
        }
        break;


    case 'generar_pdf_dependencias':
        require_once 'Controlador/DependenciaControlador/DependenciaControlador.php';
        $dependenciaControlador = new DependenciaControlador($conn);

        // Verifica si hay un filtro (ubicacion o estado) y lo pasa a la función
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
        $dependenciaControlador->generarPDF($filtro);
        break;


    case 'generar_pdf_dependencias_admin':
        require_once 'Controlador/DependenciaControlador/DependenciaControlador.php';
        $dependenciaController = new DependenciaControlador($conn);

        // Capturamos el filtro desde la URL si está presente
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';

        // Pasamos el filtro al método para generar el PDF
        $dependenciaController->generarPDF($filtro);
        break;


    case 'mostrarMobiliarioAsignado':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);

        $id_empleado = isset($_GET['id_empleado']) ? (int) $_GET['id_empleado'] : null;
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        $mobiliarioControlador->mostrarMobiliarioAsignado($id_empleado, $categoria);
        break;

    
   


    case 'generarPDF':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);

        // Obtén los filtros de empleado y categoría
        $id_empleado = isset($_GET['id_empleado']) ? $_GET['id_empleado'] : '';
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        $estado = isset($_GET['estado']) ? $_GET['estado'] : '';

        // Llama al método para generar el PDF
        $mobiliarioControlador->generarPDF($id_empleado, $categoria, $estado);
        break;

   

    // Agregar un nuevo mobiliario
    case 'agregarMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos enviados desde el formulario
            $descripcion = $_POST['descripcion'];
            $categoria = $_POST['categoria'];
            $estado = $_POST['estado'];
            $id_dependencia = $_POST['id_dependencia'];
    
            // Llamar al controlador para agregar el mobiliario
            $mobiliarioControlador->agregarMobiliario($descripcion, $categoria, $estado, $id_dependencia);
    
            // No necesitas más lógica aquí porque el controlador ya redirige.
            exit();
        }
        break;
    

    // Editar un mobiliario
    case 'editarAsignacion':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger los datos enviados desde el formulario
            $id_historial = isset($_POST['id_historial']) ? (int)$_POST['id_historial'] : null;
            $id_empleado = isset($_POST['id_empleado']) ? (int)$_POST['id_empleado'] : null;
            $id_mobiliario = isset($_POST['id_mobiliario']) ? (int)$_POST['id_mobiliario'] : null;
    
            // Validar que todos los datos necesarios existan
            if ($id_historial && $id_empleado && $id_mobiliario) {
                // Llamar al método del controlador para editar la asignación
                $mobiliarioControlador->editarAsignacion($id_historial, $id_empleado, $id_mobiliario);
            } else {
                // Si falta algún dato, redirigir con mensaje de error
                $_SESSION['mensaje'] = 'Faltan datos para editar la asignación.';
                $_SESSION['tipo_mensaje'] = 'danger';
                header("Location: index.php?action=adminMobiliario");
                exit();
            }
        }
        break;
    
    

    // Acción para desasignar el mobiliario
    case 'desasignarMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador(db: $conn);
    
        // Verificar que sea un POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el id del mobiliario a desasignar
            $id_mobiliario = $_POST['id_mobiliario'];
    
            // Llamar al controlador para desasignar el mobiliario
            $resultado = $mobiliarioControlador->desasignarMobiliario($id_mobiliario);
    
            // Almacenar el mensaje según el resultado
            if ($resultado) {
                $_SESSION['mensaje'] = 'Mobiliario desasignado correctamente.';
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = 'Hubo un error al desasignar el mobiliario.';
                $_SESSION['tipo_mensaje'] = 'danger';
            }
    
            // Redirigir a la vista de administración con el mensaje
            header('Location: index.php?action=adminMobiliario');
            exit();
        }
        break;
    

    // Acción para administrar el mobiliario

    case 'adminMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);
    
        $id_empleado = isset($_GET['id_empleado']) ? (int)$_GET['id_empleado'] : null;
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
        $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
    
        $mobiliarioControlador->mostrarAdminMobiliario($id_empleado, $categoria, $estado);
        break;
    
    
        case 'eliminarMobiliario':
            require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
            $mobiliarioControlador = new MobiliarioControlador($conn);
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_mobiliario = (int)$_POST['id_mobiliario'];
                $mobiliarioControlador->eliminarMobiliario($id_mobiliario);
            }
            break;


            case 'listarMobiliarios':
                require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
                $mobiliarioControlador = new MobiliarioControlador($conn);
            
                $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
                $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
            
                $mobiliarioControlador->mostrarTodosMobiliarios($categoria, $estado);
                break;
            



                case 'asignarMobiliario':
                    require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
                    $mobiliarioController = new MobiliarioControlador($conn);
                
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $id_empleado = $_POST['id_empleado'];
                        $id_mobiliario = $_POST['id_mobiliario'];
                
                        // Llamar al método para asignar el mobiliario
                        $mobiliarioController->asignarMobiliario($id_empleado, $id_mobiliario);
                    }
                    break;

                    
            case 'generarPDFMobiliarios':
                require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
                $mobiliarioControlador = new MobiliarioControlador($conn);
            
                $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
                $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
            
                $mobiliarioControlador->generarPDFMobiliarios($categoria, $estado);
                break;
            
        


                case 'getDataByDependencia':
                    $id_dependencia = $_GET['id_dependencia'] ?? null;
                    if ($id_dependencia) {
                        $empleados = $modelo->obtenerEmpleadosPorDependencia($id_dependencia);
                        $mobiliarios = $modelo->obtenerMobiliariosPorDependencia($id_dependencia);
                        echo json_encode(['empleados' => $empleados, 'mobiliarios' => $mobiliarios]);
                    } else {
                        echo json_encode(['error' => 'No se especificó una dependencia.']);
                    }
                    exit();
                
                
    case 'logout':
        // Cerrar sesión
        session_destroy();
        header("Location: index.php?action=zlogin");
        exit();

    default:
        // Vista de inicio (por defecto)
        include 'Vista/InicioVista/InicioVista.html';
        break;
}
?>