<?php
namespace Controlador;

use Controlador\AuthController;
use Modelo\CocheModel;
use Modelo\UserModel;

class AdminController
{
    private AuthController $authController;
    private CocheModel $cocheModel;
    private UserModel $userModel;

    public function __construct()
    {
        $this->authController = new AuthController();
        $this->cocheModel = new CocheModel();
        $this->userModel = new UserModel();
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

        $totalUsuarios = $this->userModel->contarUsuarios();
        $totalCoches = $this->cocheModel->contarVehiculos();

        // Load the admin dashboard view
        require_once __DIR__ . '/../Vista/admin/dashboard_simple.php';
    }

    public function manageUsers(): void
    {
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        $usuarios = $this->userModel->listarUsuariosAdmin();
        require_once __DIR__ . '/../Vista/admin/manage_users.php';
    }

    public function toggleUserAdmin(): void
    {
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $idUsuario = (int)($_POST['idUsuario'] ?? 0);
        $isAdmin = (int)($_POST['isAdmin'] ?? 0);

        if ($idUsuario <= 0) {
            $_SESSION['errores'] = ['Usuario inválido'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $idActual = (int)($this->authController->getUsuarioActual()['idUsuario'] ?? 0);
        if ($idActual > 0 && $idUsuario === $idActual && $isAdmin === 0) {
            $_SESSION['errores'] = ['No puedes quitarte el rol de administrador a ti mismo'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $ok = $this->userModel->actualizarRolAdmin($idUsuario, $isAdmin ? 1 : 0);
        if ($ok) {
            $_SESSION['mensaje'] = 'Rol actualizado correctamente.';
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $_SESSION['errores'] = ['No se pudo actualizar el rol del usuario'];
        header('Location: index.php?action=manageUsers');
        exit;
    }

    public function deleteUser(): void
    {
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $idUsuario = (int)($_POST['idUsuario'] ?? 0);
        if ($idUsuario <= 0) {
            $_SESSION['errores'] = ['Usuario inválido'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $idActual = (int)($this->authController->getUsuarioActual()['idUsuario'] ?? 0);
        if ($idActual > 0 && $idUsuario === $idActual) {
            $_SESSION['errores'] = ['No puedes borrar tu propio usuario'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $ok = $this->userModel->eliminarUsuarioPorId($idUsuario);
        if ($ok) {
            $_SESSION['mensaje'] = 'Usuario eliminado correctamente.';
            header('Location: index.php?action=manageUsers');
            exit;
        }

        $_SESSION['errores'] = ['No se pudo eliminar el usuario'];
        header('Location: index.php?action=manageUsers');
        exit;
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
            'potencia' => (int)($_POST['potencia'] ?? 0),
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
        if ($data['potencia'] < 0) $errores[] = 'La potencia no puede ser negativa';
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

    public function manageCars(): void
    {
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        $vehiculos = $this->cocheModel->obtenerTodosVehiculosAdmin();

        require_once __DIR__ . '/../Vista/admin/manage_cars.php';
    }

    public function deleteCar(): void
    {
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manageCars');
            exit;
        }

        $idVehiculo = (int)($_POST['idVehiculo'] ?? 0);
        if ($idVehiculo <= 0) {
            $_SESSION['errores'] = ['ID de vehículo inválido'];
            header('Location: index.php?action=manageCars');
            exit;
        }

        try {
            $ok = $this->cocheModel->eliminarVehiculo($idVehiculo);
            if ($ok) {
                $_SESSION['mensaje'] = 'Vehículo eliminado correctamente';
            } else {
                $_SESSION['errores'] = ['No se pudo eliminar el vehículo'];
            }
        } catch (\Throwable $e) {
            $_SESSION['errores'] = ['Error al eliminar el vehículo: ' . $e->getMessage()];
        }

        header('Location: index.php?action=manageCars');
        exit;
    }

    public function editCar(): void
    {
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        $idVehiculo = (int)($_GET['id'] ?? ($_POST['idVehiculo'] ?? 0));
        if ($idVehiculo <= 0) {
            $_SESSION['errores'] = ['ID de vehículo inválido'];
            header('Location: index.php?action=manageCars');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $vehiculo = $this->cocheModel->obtenerVehiculoPorIdAdmin($idVehiculo);
            if (!$vehiculo) {
                $_SESSION['errores'] = ['Vehículo no encontrado'];
                header('Location: index.php?action=manageCars');
                exit;
            }

            $marcas = $this->cocheModel->obtenerMarcas();
            $imagenes = $this->cocheModel->obtenerImagenesVehiculo($idVehiculo);

            require_once __DIR__ . '/../Vista/admin/edit_car.php';
            return;
        }

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
            'potencia' => (int)($_POST['potencia'] ?? 0),
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => $_POST['precio'] ?? '',
        ];

        $errores = [];
        if ($data['idMarca'] <= 0) $errores[] = 'La marca es obligatoria';
        if ($data['idModelo'] <= 0) $errores[] = 'El modelo es obligatorio';
        if ($data['km'] < 0) $errores[] = 'El kilometraje no puede ser negativo';
        if (empty($data['combustible'])) $errores[] = 'El combustible es obligatorio';
        if (empty($data['color'])) $errores[] = 'El color es obligatorio';
        if ($data['año'] <= 0) $errores[] = 'El año es obligatorio';
        if (empty($data['cambio'])) $errores[] = 'El cambio es obligatorio';
        if ($data['potencia'] < 0) $errores[] = 'La potencia no puede ser negativa';
        if ($data['precio'] === '' || !is_numeric($data['precio'])) $errores[] = 'El precio es obligatorio y debe ser numérico';

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: index.php?action=editCar&id=' . $idVehiculo);
            exit;
        }

        try {
            $this->cocheModel->actualizarVehiculo($idVehiculo, $data);

            $deleteImages = $_POST['delete_images'] ?? [];
            if (is_array($deleteImages)) {
                foreach ($deleteImages as $idImagenStr) {
                    $idImagen = (int)$idImagenStr;
                    if ($idImagen <= 0) continue;

                    $ruta = $this->cocheModel->obtenerRutaImagenPorId($idImagen, $idVehiculo);
                    $this->cocheModel->eliminarImagenVehiculo($idImagen, $idVehiculo);

                    if ($ruta) {
                        $pos = stripos($ruta, 'uploads');
                        if ($pos !== false) {
                            @unlink($ruta);
                        }
                    }
                }
            }

            $principalSeleccion = (string)($_POST['imagen_principal'] ?? '');
            $principalTipo = '';
            $principalId = 0;
            $principalNewIndex = -1;

            if (strpos($principalSeleccion, 'existing_') === 0) {
                $principalTipo = 'existing';
                $principalId = (int)substr($principalSeleccion, strlen('existing_'));
            } elseif (strpos($principalSeleccion, 'new_') === 0) {
                $principalTipo = 'new';
                $principalNewIndex = (int)substr($principalSeleccion, strlen('new_'));
            }

            $rutasNuevas = [];
            if (!empty($_FILES['imagenes']['name'][0])) {
                $uploadDir = __DIR__ . '/../uploads/vehiculos/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0775, true);

                foreach ($_FILES['imagenes']['name'] as $i => $name) {
                    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) continue;

                    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $name);
                    $destPath = $uploadDir . $safeName;

                    if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $destPath)) {
                        $rutasNuevas[$i] = $destPath;
                    }
                }

                if (!empty($rutasNuevas)) {
                    $imagenesGuardadas = [];
                    foreach ($rutasNuevas as $i => $ruta) {
                        $imagenesGuardadas[] = ['ruta' => $ruta, 'esPrincipal' => 0];
                    }
                    $this->cocheModel->agregarImagenesVehiculo($idVehiculo, $imagenesGuardadas);
                }
            }

            $principalSet = false;
            if ($principalTipo === 'existing' && $principalId > 0) {
                $principalSet = $this->cocheModel->setImagenPrincipalPorIdImagen($idVehiculo, $principalId);
            } elseif ($principalTipo === 'new' && $principalNewIndex >= 0 && isset($rutasNuevas[$principalNewIndex])) {
                $principalSet = $this->cocheModel->setImagenPrincipalPorRuta($idVehiculo, $rutasNuevas[$principalNewIndex]);
            }

            if (!$principalSet) {
                $imagenesRestantes = $this->cocheModel->obtenerImagenesVehiculo($idVehiculo);
                if (empty($imagenesRestantes)) {
                    $this->cocheModel->limpiarImagenPrincipal($idVehiculo);
                } else {
                    $principalActual = null;
                    foreach ($imagenesRestantes as $img) {
                        if (!empty($img['esPrincipal'])) {
                            $principalActual = $img;
                            break;
                        }
                    }

                    if (!$principalActual) {
                        $this->cocheModel->setImagenPrincipalPorIdImagen($idVehiculo, (int)$imagenesRestantes[0]['idImagen']);
                    }
                }
            }

            $_SESSION['mensaje'] = 'Vehículo actualizado correctamente';
            header('Location: index.php?action=manageCars');
            exit;
        } catch (\Throwable $e) {
            $_SESSION['errores'] = ['Error al actualizar el vehículo: ' . $e->getMessage()];
            header('Location: index.php?action=editCar&id=' . $idVehiculo);
            exit;
        }
    }
}
