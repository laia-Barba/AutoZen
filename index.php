<?php
// Iniciar la sesión para poder usar variables de sesión
session_start();

// Activar logging de errores para depuración
error_log("=== Nueva Petición ===");
error_log("Método de la petición: " . $_SERVER['REQUEST_METHOD']);
error_log("URI de la petición: " . $_SERVER['REQUEST_URI']);
error_log("Parámetro action: " . ($_GET['action'] ?? 'no definido'));

// Incluir los archivos necesarios para el funcionamiento
require_once 'app/Core/Database.php';          // Conexión a la base de datos
require_once 'Modelo/CocheModel.php';         // Modelo para gestionar vehículos
require_once 'Modelo/UserModel.php';          // Modelo para gestionar usuarios
require_once 'Modelo/CarritoModel.php';       // Modelo para gestionar el carrito
require_once 'Controlador/HomeController.php';   // Controlador principal de la web
require_once 'Controlador/AuthController.php';    // Controlador de autenticación
require_once 'Controlador/AdminController.php';   // Controlador de administración

// Usar los namespaces de los controladores
use Controlador\HomeController;
use Controlador\AuthController;
use Controlador\AdminController;

// Obtener la acción a ejecutar desde la URL, por defecto 'index'
$action = $_GET['action'] ?? 'index';

error_log("Procesando acción: " . $action);

// Crear instancias de los controladores
$homeController = new HomeController();    // Controlador para páginas públicas
$authController = new AuthController();     // Controlador para login/registro/perfil
$adminController = new AdminController();   // Controlador para administración

// Router principal: determina qué controlador y método ejecutar según la acción
switch ($action) {
    case 'index':
        error_log("Ejecutando caso: index");
        $homeController->index();           // Página principal
        break;
    case 'buscar':
        error_log("Ejecutando caso: buscar");
        $homeController->buscar();           // Página de búsqueda de vehículos
        break;
    case 'detalle':
        error_log("Ejecutando caso: detalle");
        $homeController->detalle();          // Página de detalles de un vehículo
        break;
    case 'getModelos':
        error_log("Ejecutando caso: getModelos");
        $homeController->getModelos();       // AJAX: obtener modelos de una marca
        break;
    case 'getModelos':
        error_log("Ejecutando caso: getModelos");
        $homeController->getModelos();       // AJAX: obtener modelos de una marca (duplicado)
        break;
    case 'login':
        error_log("Ejecutando caso: login");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Petición POST - llamando al método login");
            $authController->login();       // Procesar formulario de login
        } else {
            error_log("Petición GET - mostrando formulario de login");
            $authController->mostrarLogin(); // Mostrar página de login
        }
        break;
    case 'forgotPassword':
        error_log("Ejecutando caso: forgotPassword");
        $authController->mostrarRecuperarContrasenaPaso1(); // Paso 1 recuperación contraseña
        break;
    case 'forgotPasswordValidate':
        error_log("Ejecutando caso: forgotPasswordValidate");
        $authController->validarClaveRecuperarContrasena(); // Validar email recuperación
        break;
    case 'forgotPasswordNew':
        error_log("Ejecutando caso: forgotPasswordNew");
        $authController->mostrarRecuperarContrasenaPaso2(); // Paso 2 recuperación contraseña
        break;
    case 'forgotPasswordSave':
        error_log("Ejecutando caso: forgotPasswordSave");
        $authController->guardarRecuperarContrasena(); // Guardar nueva contraseña
        break;
    case 'registro':
        error_log("Ejecutando caso: registro");
        $authController->mostrarRegistro(); // Mostrar formulario de registro
        break;
    case 'registrar':
        error_log("Ejecutando caso: registrar");
        $authController->registrar(); // Procesar formulario de registro
        break;
    case 'checkEmail':
        error_log("Ejecutando caso: checkEmail");
        $authController->checkEmail(); // AJAX: verificar si email existe
        break;
    case 'logout':
        error_log("Ejecutando caso: logout");
        $authController->logout(); // Cerrar sesión
        break;
    case 'perfil':
        error_log("Ejecutando caso: perfil");
        $authController->mostrarPerfil(); // Mostrar perfil de usuario
        break;
    case 'editProfile':
        error_log("Ejecutando caso: editProfile");
        $authController->mostrarEditarPerfil(); // Mostrar formulario editar perfil
        break;
    case 'saveProfile':
        error_log("Ejecutando caso: saveProfile");
        $authController->guardarEditarPerfil(); // Guardar cambios del perfil
        break;
    case 'carrito':
        error_log("Ejecutando caso: carrito");
        $authController->mostrarCarrito(); // Mostrar página del carrito
        break;
    case 'carritoSave':
        error_log("Ejecutando caso: carritoSave");
        $authController->guardarCarrito(); // Guardar datos de contacto del carrito
        break;
    case 'cartAdd':
        error_log("Ejecutando caso: cartAdd");
        $authController->cartAdd(); // AJAX: añadir vehículo al carrito
        break;
    case 'reservarVehiculo':
        error_log("Ejecutando caso: reservarVehiculo");
        $authController->reservarVehiculo(); // AJAX: reservar vehículo
        break;
    case 'cancelarReserva':
        error_log("Ejecutando caso: cancelarReserva");
        $authController->cancelarReserva(); // AJAX: cancelar reserva
        break;
    case 'eliminarDelCarrito':
        error_log("Ejecutando caso: eliminarDelCarrito");
        $authController->eliminarDelCarrito(); // AJAX: eliminar vehículo del carrito
        break;
    case 'changePassword':
        error_log("Ejecutando caso: changePassword");
        $authController->mostrarCambioContrasenaPaso1(); // Paso 1 cambio contraseña
        break;
    case 'validatePasswordKey':
        error_log("Ejecutando caso: validatePasswordKey");
        $authController->validarClaveCambioContrasena(); // Validar clave cambio
        break;
    case 'changePasswordNew':
        error_log("Ejecutando caso: changePasswordNew");
        $authController->mostrarCambioContrasenaPaso2(); // Paso 2 cambio contraseña
        break;
    case 'changePasswordSave':
        error_log("Ejecutando caso: changePasswordSave");
        $authController->guardarNuevaContrasena(); // Guardar nueva contraseña
        break;
    case 'admin':
        error_log("Ejecutando caso: admin");
        $adminController->dashboard(); // Panel de administración
        break;
    case 'manageUsers':
        error_log("Ejecutando caso: manageUsers");
        $adminController->manageUsers(); // Gestionar usuarios
        break;
    case 'toggleUserAdmin':
        error_log("Ejecutando caso: toggleUserAdmin");
        $adminController->toggleUserAdmin(); // Cambiar rol admin a usuario
        break;
    case 'deleteUser':
        error_log("Ejecutando caso: deleteUser");
        $adminController->deleteUser(); // Eliminar usuario
        break;
    case 'addCar':
        error_log("Ejecutando caso: addCar");
        $adminController->addCar(); // Añadir nuevo vehículo
        break;
    case 'manageCars':
        error_log("Ejecutando caso: manageCars");
        $adminController->manageCars(); // Gestionar vehículos
        break;
    case 'deleteCar':
        error_log("Ejecutando caso: deleteCar");
        $adminController->deleteCar(); // Eliminar vehículo
        break;
    case 'editCar':
        error_log("Ejecutando caso: editCar");
        $adminController->editCar(); // Editar vehículo
        break;
    default:
        error_log("Acción no reconocida: " . $action);
        // Si la acción no existe, redirigir a la página principal
        header('Location: index.php');
        exit;
}
