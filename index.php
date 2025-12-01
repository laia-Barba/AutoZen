<?php
require_once 'app/Core/Database.php';
require_once 'Modelo/CocheModel.php';
require_once 'Controlador/HomeController.php';

use Controlador\HomeController;

$action = $_GET['action'] ?? 'index';

$controller = new HomeController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'buscar':
        $controller->buscar();
        break;
    case 'getModelos':
        $controller->getModelos();
        break;
    default:
        $controller->index();
        break;
}
