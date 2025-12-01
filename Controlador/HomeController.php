<?php
namespace Controlador;

use Modelo\CocheModel;

class HomeController
{
    private CocheModel $cocheModel;

    public function __construct()
    {
        $this->cocheModel = new CocheModel();
    }

    public function index(): void
    {
        try {
            // Obtener datos para la página principal
            $cochesDestacados = $this->cocheModel->obtenerCochesDestacados(6);
            $marcas = $this->cocheModel->obtenerMarcas();
            $estadisticas = $this->cocheModel->obtenerEstadisticas();

            // Cargar la vista con los datos
            require_once __DIR__ . '/../Vista/home.php';
        } catch (Exception $e) {
            // En caso de error, mostrar una página de error simple
            $error_message = $e->getMessage();
            require_once __DIR__ . '/../Vista/error.php';
        }
    }

    public function buscar(): void
    {
        $filtros = [];
        
        if (isset($_GET['marca'])) {
            $filtros['marca'] = filter_input(INPUT_GET, 'marca', FILTER_VALIDATE_INT);
        }
        
        if (isset($_GET['modelo'])) {
            $filtros['modelo'] = filter_input(INPUT_GET, 'modelo', FILTER_VALIDATE_INT);
        }
        
        if (isset($_GET['precio_min'])) {
            $filtros['precio_min'] = filter_input(INPUT_GET, 'precio_min', FILTER_VALIDATE_FLOAT);
        }
        
        if (isset($_GET['precio_max'])) {
            $filtros['precio_max'] = filter_input(INPUT_GET, 'precio_max', FILTER_VALIDATE_FLOAT);
        }
        
        if (isset($_GET['kilometraje_max'])) {
            $filtros['kilometraje_max'] = filter_input(INPUT_GET, 'kilometraje_max', FILTER_VALIDATE_INT);
        }
        
        if (isset($_GET['anio_min'])) {
            $filtros['año_min'] = filter_input(INPUT_GET, 'anio_min', FILTER_VALIDATE_INT);
        }
        
        if (isset($_GET['año_max'])) {
            $filtros['año_max'] = filter_input(INPUT_GET, 'año_max', FILTER_VALIDATE_INT);
        }
        
        if (isset($_GET['combustible'])) {
            $filtros['combustible'] = filter_input(INPUT_GET, 'combustible', FILTER_SANITIZE_STRING);
        }

        $resultados = $this->cocheModel->buscarCoches($filtros);
        $marcas = $this->cocheModel->obtenerMarcas();

        require_once __DIR__ . '/../Vista/busqueda.php';
    }

    public function getModelos(): void
    {
        header('Content-Type: application/json');
        
        $marcaId = filter_input(INPUT_GET, 'marca', FILTER_VALIDATE_INT);
        
        if (!$marcaId) {
            echo json_encode([]);
            return;
        }
        
        try {
            $modelos = $this->cocheModel->obtenerModelosPorMarca($marcaId);
            echo json_encode($modelos);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
}
