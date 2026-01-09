<?php
namespace Controlador;

use Modelo\CocheModel;

class HomeController
{
    private CocheModel $cocheModel;
    private AuthController $authController;

    public function __construct()
    {
        $this->cocheModel = new CocheModel();
        $this->authController = new AuthController();
    }

    public function index(): void
    {
        try {
            // Obtener datos para la página principal
            $cochesDestacados = $this->cocheModel->obtenerCochesDestacados(6);
            $marcas = $this->cocheModel->obtenerMarcas();
            $estadisticas = $this->cocheModel->obtenerEstadisticas();
            
            // Obtener datos de autenticación
            $estaLogueado = $this->authController->estaLogueado();
            $usuarioActual = $estaLogueado ? $this->authController->getUsuarioActual() : null;
            $esAdmin = $estaLogueado ? $this->authController->esAdmin() : false;

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
        
        // Obtener datos de autenticación
        $estaLogueado = $this->authController->estaLogueado();
        $usuarioActual = $estaLogueado ? $this->authController->getUsuarioActual() : null;
        $esAdmin = $estaLogueado ? $this->authController->esAdmin() : false;

        // Check if this is an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            // Return only the results HTML for AJAX requests
            $this->renderResults($resultados);
        } else {
            // Return full page for regular requests
            require_once __DIR__ . '/../Vista/busqueda.php';
        }
    }

    public function detalle(): void
    {
        $idVehiculo = filter_input(INPUT_GET, 'idVehiculo', FILTER_VALIDATE_INT);
        if (!$idVehiculo) {
            header('Location: index.php');
            exit;
        }

        $vehiculo = $this->cocheModel->obtenerVehiculoDetalle((int)$idVehiculo);
        if (!$vehiculo) {
            header('Location: index.php');
            exit;
        }

        $imagenes = $this->cocheModel->obtenerImagenesVehiculoPublic((int)$idVehiculo);

        $estaLogueado = $this->authController->estaLogueado();
        $usuarioActual = $estaLogueado ? $this->authController->getUsuarioActual() : null;
        $esAdmin = $estaLogueado ? $this->authController->esAdmin() : false;

        require_once __DIR__ . '/../Vista/detalle.php';
    }

    private function renderResults(array $resultados): void
    {
        ob_start();
        if (empty($resultados)) {
            ?>
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="no-results-title">No se encontraron resultados</h3>
                <p class="no-results-text">
                    No hay coches que coincidan con los criterios de búsqueda seleccionados.
                    Intenta ajustar los filtros para ver más opciones.
                </p>
                <a href="index.php" class="btn-primary-custom">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <?php foreach ($resultados as $coche): ?>
                    <div class="col-lg-6">
                        <div class="car-card">
                            <div class="car-image-container">
                                <?php
                                    $imgPath = isset($coche['imagen']) && $coche['imagen']
                                        ? $coche['imagen']
                                        : 'https://via.placeholder.com/400x300/FF6B35/FFFFFF?text=' . urlencode($coche['marca'] . ' ' . $coche['modelo']);

                                    // Normalizar a ruta web (la BD puede guardar rutas tipo C:\xampp\htdocs\...)
                                    if (strpos($imgPath, 'http') === 0) {
                                        $img = $imgPath;
                                    } else {
                                        $p = str_replace('\\', '/', (string)$imgPath);

                                        if (($pos = stripos($p, '/htdocs/')) !== false) {
                                            $rel = substr($p, $pos + strlen('/htdocs/'));
                                            $img = '/' . ltrim($rel, '/');
                                        } elseif (($pos = stripos($p, '/AutoZen/')) !== false) {
                                            $rel = substr($p, $pos);
                                            $img = $rel;
                                        } else {
                                            $img = '/' . ltrim($p, '/');
                                        }

                                        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
                                        if ($base && strpos($img, $base) !== 0) {
                                            $img = $base . $img;
                                        }
                                    }
                                ?>
                                <img src="<?php echo htmlspecialchars($img); ?>" 
                                     alt="<?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?>" 
                                     class="car-image">
                            </div>
                            <div class="car-details">
                                <h3 class="car-title">
                                    <?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?>
                                </h3>
                                <div class="car-specs">
                                    <div class="spec-item">
                                        <i class="fas fa-calendar"></i>
                                        <span><?php echo isset($coche['año']) ? (int)$coche['año'] : ''; ?></span>
                                    </div>
                                    <div class="spec-item">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span><?php echo isset($coche['km']) ? number_format($coche['km'], 0, ',', '.') : '0'; ?> km</span>
                                    </div>
                                    <div class="spec-item">
                                        <i class="fas fa-gas-pump"></i>
                                        <span><?php echo htmlspecialchars($coche['combustible']); ?></span>
                                    </div>
                                    <div class="spec-item">
                                        <i class="fas fa-cog"></i>
                                        <span><?php echo htmlspecialchars($coche['cambio'] ?? 'Manual'); ?></span>
                                    </div>
                                </div>
                                <div class="car-price">
                                    <?php echo number_format($coche['precio'], 0, ',', '.'); ?>€
                                </div>
                                <div class="car-actions">
                                    <a class="btn-primary-custom" href="index.php?action=detalle&idVehiculo=<?php echo (int)$coche['idVehiculo']; ?>">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </a>
                                    <button class="btn-secondary-custom">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
        }
        echo ob_get_clean();
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
