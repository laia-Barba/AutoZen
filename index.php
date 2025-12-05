<?php
session_start();

// Enable error logging for debugging
error_log("=== New Request ===");
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Action parameter: " . ($_GET['action'] ?? 'not set'));

require_once 'app/Core/Database.php';
require_once 'Modelo/CocheModel.php';
require_once 'Modelo/UserModel.php';
require_once 'Controlador/HomeController.php';
require_once 'Controlador/AuthController.php';

use Controlador\HomeController;
use Controlador\AuthController;

$action = $_GET['action'] ?? 'index';

error_log("Processing action: " . $action);

$homeController = new HomeController();
$authController = new AuthController();

switch ($action) {
    case 'index':
        error_log("Executing index case");
        $homeController->index();
        break;
    case 'buscar':
        error_log("Executing buscar case");
        $homeController->buscar();
        break;
    case 'getModelos':
        error_log("Executing getModelos case");
        $homeController->getModelos();
        break;
    case 'login':
        error_log("Executing login case");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST request - calling login method");
            $authController->login();
        } else {
            error_log("GET request - calling mostrarLogin method");
            $authController->mostrarLogin();
        }
        break;
    case 'registro':
        error_log("Executing registro case");
        $authController->mostrarRegistro();
        break;
    case 'registrar':
        error_log("Executing registrar case");
        $authController->registrar();
        break;
    case 'logout':
        error_log("Executing logout case");
        $authController->logout();
        break;
    case 'perfil':
        error_log("Executing perfil case");
        $authController->mostrarPerfil();
        break;
    default:
        error_log("Executing default case");
        $homeController->index();
        break;
}
