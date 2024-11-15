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

        // Llama al método para generar el PDF
        $mobiliarioControlador->generarPDF($id_empleado, $categoria);
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

        // Llama al método para generar el PDF
        $mobiliarioControlador->generarPDF($id_empleado, $categoria);
        break;

    case 'mostrarMobiliarioAsignado':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);

        // Obtén los filtros de empleado y categoría
        $id_empleado = isset($_GET['id_empleado']) ? $_GET['id_empleado'] : null;
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        // Llama al método para mostrar mobiliarios asignados
        $mobiliarioControlador->mostrarAdminMobiliario($id_empleado, $categoria);
        break;

    // Agregar un nuevo mobiliario
    case 'agregarMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir los datos del formulario
            $id_empleado = $_POST['id_empleado'];
            $mobiliario = $_POST['mobiliario'];
            $categoria = $_POST['categoria'];
            $estado = $_POST['estado'];

            // Llamar al controlador para agregar el mobiliario
            $mobiliarioControlador = new MobiliarioControlador($conn);
            $mobiliarioControlador->agregarMobiliario($id_empleado, $mobiliario, $categoria, $estado);
            header('Location: index.php?action=mostrarMobiliarioAsignado'); // Redirigir a la vista de mobiliarios
        }
        break;

    // Editar un mobiliario
    case 'editarMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir los datos del formulario
            $id_mobiliario = $_POST['id_mobiliario'];
            $id_empleado = $_POST['id_empleado'];
            $mobiliario = $_POST['mobiliario'];
            $categoria = $_POST['categoria'];
            $estado = $_POST['estado'];

            // Llamar al controlador para editar el mobiliario
            $mobiliarioControlador = new MobiliarioControlador($conn);
            $mobiliarioControlador->editarMobiliario($id_mobiliario, $id_empleado, $mobiliario, $categoria, $estado);
            header('Location: index.php?action=mostrarMobiliarioAsignado'); // Redirigir a la vista de mobiliarios
        }
        break;

    // Desasignar un mobiliario (Eliminar)
    // Acción para desasignar el mobiliario
    case 'desasignarMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);

        // Verificar que sea un POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el id del mobiliario a desasignar
            $id_mobiliario = $_POST['id_mobiliario'];

            // Llamar al controlador para desasignar el mobiliario
            $resultado = $mobiliarioControlador->desasignarMobiliario($id_mobiliario);

            // Si la desasignación fue exitosa, almacenar el mensaje de éxito en la sesión
            if ($resultado) {
                $_SESSION['mensaje'] = 'Mobiliario desasignado correctamente.';
            } else {
                $_SESSION['mensaje'] = 'Hubo un error al desasignar el mobiliario.';
            }

            // Redirigir a la vista de administración
            header('Location: index.php?action=adminMobiliario');
            exit();
        }
        break;

        break;

    // Acción para administrar el mobiliario

    case 'adminMobiliario':
        require_once 'Controlador/MobiliarioControlador/MobiliarioControlador.php';
        $mobiliarioControlador = new MobiliarioControlador($conn);

        $id_empleado = isset($_GET['id_empleado']) ? $_GET['id_empleado'] : null;
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        // Llamar al método correcto para mostrar la vista de administración
        $mobiliarioControlador->mostrarAdminMobiliario($id_empleado, $categoria);
        break;



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