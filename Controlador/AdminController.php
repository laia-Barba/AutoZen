<?php
namespace Controlador;

use Controlador\AuthController;
use Modelo\CocheModel;

class AdminController
{
    private AuthController $authController;
    private CocheModel $cocheModel;

    public function __construct()
    {
        $this->authController = new AuthController();
        $this->cocheModel = new CocheModel();
    }

    public function dashboard(): void
    {
        // Check if user is logged in and is admin
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Get user data for the dashboard
        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = $this->authController->esAdmin();

        // Load the admin dashboard view
        require_once __DIR__ . '/../Vista/admin/dashboard_simple.php';
    }

    public function addCar(): void
    {
        // Solo admins
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Cargar datos para el formulario
            $marcas = $this->cocheModel->obtenerMarcas();
            // modelos se cargarán por AJAX según marca
            require_once __DIR__ . '/../Vista/admin/add_car.php';
            return;
        }

        // POST: procesar creación
        $data = [
            'idMarca' => (int)($_POST['idMarca'] ?? 0),
            'idModelo' => (int)($_POST['idModelo'] ?? 0),
            'km' => (int)($_POST['km'] ?? 0),
            'combustible' => $_POST['combustible'] ?? '',
            'color' => $_POST['color'] ?? '',
            'año' => (int)($_POST['anio'] ?? 0),
            'cambio' => $_POST['cambio'] ?? '',
            'consumo' => $_POST['consumo'] ?? '',
            'motor' => $_POST['motor'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => $_POST['precio'] ?? '',
            'imagen' => null,
        ];

        $errores = [];
        if ($data['idMarca'] <= 0) $errores[] = 'La marca es obligatoria';
        if ($data['idModelo'] <= 0) $errores[] = 'El modelo es obligatorio';
        if ($data['km'] < 0) $errores[] = 'El kilometraje no puede ser negativo';
        if (empty($data['combustible'])) $errores[] = 'El combustible es obligatorio';
        if (empty($data['color'])) $errores[] = 'El color es obligatorio';
        if ($data['año'] <= 0) $errores[] = 'El año es obligatorio';
        if (empty($data['cambio'])) $errores[] = 'El cambio es obligatorio';
        if ($data['precio'] === '' || !is_numeric($data['precio'])) $errores[] = 'El precio es obligatorio y debe ser numérico';

        // El manejo de imágenes se hará después de crear el vehículo
        $data['imagen'] = null; // Se establecerá después con la principal

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=addCar');
            exit;
        }

        try {
            $idVehiculo = $this->cocheModel->crearVehiculo($data);

            // Ahora, manejar las imágenes
            if (!empty($_FILES['imagenes']['name'][0])) {
                $uploadDir = __DIR__ . '/../uploads/vehiculos/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0775, true);

                $imagenesGuardadas = [];
                $indicePrincipal = isset($_POST['imagen_principal']) ? (int)$_POST['imagen_principal'] : 0;
                $rutaPrincipal = '';

                foreach ($_FILES['imagenes']['name'] as $i => $name) {
                    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) continue;

                    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $name);
                    $destPath = $uploadDir . $safeName;

                    if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $destPath)) {
                        $esPrincipal = ($i === $indicePrincipal);
                        $imagenesGuardadas[] = ['ruta' => $destPath, 'esPrincipal' => $esPrincipal ? 1 : 0];
                        if ($esPrincipal) {
                            $rutaPrincipal = $destPath;
                        }
                    }
                }

                if (!empty($imagenesGuardadas)) {
                    $this->cocheModel->agregarImagenesVehiculo($idVehiculo, $imagenesGuardadas);
                    if ($rutaPrincipal) {
                        $this->cocheModel->updateImagenPrincipal($idVehiculo, $rutaPrincipal);
                    }
                }
            }

            $_SESSION['mensaje'] = 'Vehículo creado correctamente (ID ' . $idVehiculo . ')';
            header('Location: index.php?action=admin');
            exit;
        } catch (\Throwable $e) {
            $_SESSION['errores'] = ['Error al guardar el vehículo: ' . $e->getMessage()];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=addCar');
            exit;
        }
    }
}
