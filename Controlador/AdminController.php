<?php
namespace Controlador;

use Controlador\AuthController;
use Modelo\CocheModel;
use Modelo\UserModel;

class AdminController
{
    private AuthController $authController;    // Para verificar login y permisos de admin
    private CocheModel $cocheModel;          // Para operaciones con vehículos
    private UserModel $userModel;            // Para operaciones con usuarios

    /**
     * Constructor: inicializa los controladores necesarios
     */
    public function __construct()
    {
        $this->authController = new AuthController();  // Verificación de autenticación
        $this->cocheModel = new CocheModel();        // Gestión de vehículos
        $this->userModel = new UserModel();            // Gestión de usuarios
    }

    /**
     * Muestra el panel principal de administración
     * Requiere estar logueado y ser administrador
     */
    public function dashboard(): void
    {
        // Verificar que el usuario esté logueado y sea administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');  // Redirigir si no tiene permisos
            exit;
        }

        // Obtener datos del usuario actual
        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = $this->authController->esAdmin();

        // Obtener estadísticas para el dashboard
        $totalUsuarios = $this->userModel->contarUsuarios();    // Total de usuarios registrados
        $totalCoches = $this->cocheModel->contarVehiculos();   // Total de vehículos en el sistema

        // Cargar la vista del dashboard con los datos
        require_once __DIR__ . '/../Vista/admin/dashboard_simple.php';
    }

    /**
     * Muestra la página de gestión de usuarios
     * Lista todos los usuarios con opciones de edición y eliminación
     */
    public function manageUsers(): void
    {
        // Verificar permisos de administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Obtener datos del usuario actual
        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;  // En esta vista, el usuario siempre es admin

        // Obtener lista de todos los usuarios
        $usuarios = $this->userModel->listarUsuariosAdmin();
        
        // Cargar vista de gestión de usuarios
        require_once __DIR__ . '/../Vista/admin/manage_users.php';
    }

    /**
     * Cambia el rol de administrador de un usuario
     * Procesa el formulario para dar/quitar permisos de admin
     */
    public function toggleUserAdmin(): void
    {
        // Verificar permisos de administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Obtener y validar datos del formulario
        $idUsuario = (int)($_POST['idUsuario'] ?? 0);      // ID del usuario a modificar
        $isAdmin = (int)($_POST['isAdmin'] ?? 0);         // Nuevo rol (1=admin, 0=usuario normal)

        // Validar que el ID del usuario sea válido
        if ($idUsuario <= 0) {
            $_SESSION['errores'] = ['Usuario inválido'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Obtener ID del usuario actual para seguridad
        $idActual = (int)($this->authController->getUsuarioActual()['idUsuario'] ?? 0);
        
        // Evitar que un administrador se quite a sí mismo el rol de admin
        if ($idActual > 0 && $idUsuario === $idActual && $isAdmin === 0) {
            $_SESSION['errores'] = ['No puedes quitarte el rol de administrador a ti mismo'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Actualizar el rol del usuario en la base de datos
        $ok = $this->userModel->actualizarRolAdmin($idUsuario, $isAdmin ? 1 : 0);
        
        if ($ok) {
            $_SESSION['mensaje'] = 'Rol actualizado correctamente.';
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Si hubo error al actualizar
        $_SESSION['errores'] = ['No se pudo actualizar el rol del usuario'];
        header('Location: index.php?action=manageUsers');
        exit;
    }

    /**
     * Elimina un usuario del sistema
     * Procesa la solicitud de eliminación con validaciones de seguridad
     */
    public function deleteUser(): void
    {
        // Verificar permisos de administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Verificar que sea una petición POST (seguridad)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Obtener y validar ID del usuario a eliminar
        $idUsuario = (int)($_POST['idUsuario'] ?? 0);
        if ($idUsuario <= 0) {
            $_SESSION['errores'] = ['Usuario inválido'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Obtener ID del usuario actual para seguridad
        $idActual = (int)($this->authController->getUsuarioActual()['idUsuario'] ?? 0);
        
        // Evitar que un usuario se elimine a sí mismo
        if ($idActual > 0 && $idUsuario === $idActual) {
            $_SESSION['errores'] = ['No puedes borrar tu propio usuario'];
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Intentar eliminar el usuario de la base de datos
        $ok = $this->userModel->eliminarUsuarioPorId($idUsuario);
        
        if ($ok) {
            $_SESSION['mensaje'] = 'Usuario eliminado correctamente.';
            header('Location: index.php?action=manageUsers');
            exit;
        }

        // Si hubo error al eliminar
        $_SESSION['errores'] = ['No se pudo eliminar el usuario'];
        header('Location: index.php?action=manageUsers');
        exit;
    }

    /**
     * Muestra el formulario para añadir un nuevo vehículo
     * Procesa tanto el GET (mostrar formulario) como el POST (procesar datos)
     */
    public function addCar(): void
    {
        // Verificar que el usuario sea administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Obtener datos del usuario actual
        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        // Si es GET: mostrar formulario para añadir vehículo
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Cargar lista de marcas para el select del formulario
            $marcas = $this->cocheModel->obtenerMarcas();
            // modelos se cargarán por AJAX según marca
            require_once __DIR__ . '/../Vista/admin/add_car.php';
            return;
        }

        // Si es POST: procesar el formulario de creación
        // Recopilar y sanitizar datos del formulario
        $data = [
            'idMarca' => (int)($_POST['idMarca'] ?? 0),         // Marca del vehículo
            'idModelo' => (int)($_POST['idModelo'] ?? 0),       // Modelo del vehículo
            'km' => (int)($_POST['km'] ?? 0),                   // Kilometraje
            'combustible' => $_POST['combustible'] ?? '',          // Tipo de combustible
            'color' => $_POST['color'] ?? '',                      // Color del vehículo
            'año' => (int)($_POST['anio'] ?? 0),               // Año del vehículo
            'cambio' => $_POST['cambio'] ?? '',                  // Tipo de cambio
            'consumo' => $_POST['consumo'] ?? '',                // Consumo
            'motor' => $_POST['motor'] ?? '',                      // Tipo de motor
            'potencia' => (int)($_POST['potencia'] ?? 0),       // Potencia en CV
            'descripcion' => $_POST['descripcion'] ?? '',           // Descripción
            'precio' => $_POST['precio'] ?? '',                    // Precio
            'imagen' => null,                                       // Se establecerá después con la imagen principal
        ];

        // Validar todos los campos obligatorios
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
        $data['imagen'] = null; // Se establecerá después con la imagen principal

        // Si hay errores de validación, volver al formulario con los errores
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;  // Mantener datos del formulario
            header('Location: index.php?action=addCar');
            exit;
        }

        // Intentar crear el vehículo en la base de datos
        try {
            $idVehiculo = $this->cocheModel->crearVehiculo($data);

            // Ahora, manejar las imágenes subidas
            if (!empty($_FILES['imagenes']['name'][0])) {
                // Crear directorio de subida si no existe
                $uploadDir = __DIR__ . '/../uploads/vehiculos/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0775, true);

                $imagenesGuardadas = [];
                $indicePrincipal = isset($_POST['imagen_principal']) ? (int)$_POST['imagen_principal'] : 0;
                $rutaPrincipal = '';

                // Procesar cada imagen subida
                foreach ($_FILES['imagenes']['name'] as $i => $name) {
                    // Verificar que no haya error en la subida
                    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) continue;

                    // Crear nombre seguro para el archivo (timestamp + nombre limpio)
                    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $name);
                    $destPath = $uploadDir . $safeName;

                    // Mover archivo temporal a destino final
                    if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $destPath)) {
                        $esPrincipal = ($i === $indicePrincipal);  // ¿Es esta la imagen principal?
                        $imagenesGuardadas[] = ['ruta' => $destPath, 'esPrincipal' => $esPrincipal ? 1 : 0];
                        if ($esPrincipal) {
                            $rutaPrincipal = $destPath;  // Guardar ruta de la imagen principal
                        }
                    }
                }

                // Si se guardaron imágenes, procesarlas en la base de datos
                if (!empty($imagenesGuardadas)) {
                    $this->cocheModel->agregarImagenesVehiculo($idVehiculo, $imagenesGuardadas);
                    if ($rutaPrincipal) {
                        $this->cocheModel->updateImagenPrincipal($idVehiculo, $rutaPrincipal);
                    }
                }
            }

            // Mostrar mensaje de éxito y redirigir al panel
            $_SESSION['mensaje'] = 'Vehículo creado correctamente (ID ' . $idVehiculo . ')';
            header('Location: index.php?action=admin');
            exit;
        } catch (\Throwable $e) {
            // Si hubo error, mostrar mensaje y volver al formulario
            $_SESSION['errores'] = ['Error al guardar el vehículo: ' . $e->getMessage()];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=addCar');
            exit;
        }
    }

    /**
     * Muestra la página de gestión de vehículos
     * Lista todos los vehículos con opciones de edición y eliminación
     */
    public function manageCars(): void
    {
        // Verificar permisos de administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Obtener datos del usuario actual
        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        // Obtener todos los vehículos del sistema
        $vehiculos = $this->cocheModel->obtenerTodosVehiculosAdmin();

        // Cargar vista de gestión de vehículos
        require_once __DIR__ . '/../Vista/admin/manage_cars.php';
    }

    /**
     * Elimina un vehículo del sistema
     * Procesa la solicitud de eliminación con manejo de errores
     */
    public function deleteCar(): void
    {
        // Verificar permisos de administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=manageCars');
            exit;
        }

        // Validar ID del vehículo a eliminar
        $idVehiculo = (int)($_POST['idVehiculo'] ?? 0);
        if ($idVehiculo <= 0) {
            $_SESSION['errores'] = ['ID de vehículo inválido'];
            header('Location: index.php?action=manageCars');
            exit;
        }

        // Intentar eliminar el vehículo con manejo de errores
        try {
            $ok = $this->cocheModel->eliminarVehiculo($idVehiculo);
            if ($ok) {
                $_SESSION['mensaje'] = 'Vehículo eliminado correctamente';
            } else {
                $_SESSION['errores'] = ['No se pudo eliminar el vehículo'];
            }
        } catch (\Throwable $e) {
            // Capturar cualquier error durante la eliminación
            $_SESSION['errores'] = ['Error al eliminar el vehículo: ' . $e->getMessage()];
        }

        // Redirigir a la lista de vehículos
        header('Location: index.php?action=manageCars');
        exit;
    }

    /**
     * Muestra el formulario para editar un vehículo existente
     * Procesa tanto GET (mostrar formulario) como POST (actualizar datos)
     */
    public function editCar(): void
    {
        // Verificar permisos de administrador
        if (!$this->authController->estaLogueado() || !$this->authController->esAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Obtener datos del usuario actual
        $usuarioActual = $this->authController->getUsuarioActual();
        $esAdmin = true;

        // Obtener ID del vehículo desde GET o POST
        $idVehiculo = (int)($_GET['id'] ?? ($_POST['idVehiculo'] ?? 0));
        if ($idVehiculo <= 0) {
            $_SESSION['errores'] = ['ID de vehículo inválido'];
            header('Location: index.php?action=manageCars');
            exit;
        }

        // Si es GET: mostrar formulario con datos actuales del vehículo
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtener datos del vehículo a editar
            $vehiculo = $this->cocheModel->obtenerVehiculoPorIdAdmin($idVehiculo);
            if (!$vehiculo) {
                $_SESSION['errores'] = ['Vehículo no encontrado'];
                header('Location: index.php?action=manageCars');
                exit;
            }

            // Obtener datos adicionales para el formulario
            $marcas = $this->cocheModel->obtenerMarcas();
            $imagenes = $this->cocheModel->obtenerImagenesVehiculo($idVehiculo);

            // Cargar vista de edición con todos los datos
            require_once __DIR__ . '/../Vista/admin/edit_car.php';
            return;
        }

        // Si es POST: procesar actualización del vehículo
        // Recopilar datos del formulario
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

        // Validar campos obligatorios
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

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: index.php?action=editCar&id=' . $idVehiculo);
            exit;
        }

        // Procesar actualización con manejo de imágenes
        try {
            // Actualizar datos básicos del vehículo
            $this->cocheModel->actualizarVehiculo($idVehiculo, $data);

            // Eliminar imágenes marcadas para borrar
            $deleteImages = $_POST['delete_images'] ?? [];
            if (is_array($deleteImages)) {
                foreach ($deleteImages as $idImagenStr) {
                    $idImagen = (int)$idImagenStr;
                    if ($idImagen <= 0) continue;

                    // Obtener ruta de la imagen y eliminarla del servidor
                    $ruta = $this->cocheModel->obtenerRutaImagenPorId($idImagen, $idVehiculo);
                    $this->cocheModel->eliminarImagenVehiculo($idImagen, $idVehiculo);

                    // Eliminar archivo físico del servidor
                    if ($ruta) {
                        $pos = stripos($ruta, 'uploads');
                        if ($pos !== false) {
                            @unlink($ruta);  // Eliminar archivo del sistema de archivos
                        }
                    }
                }
            }

            // Procesar selección de imagen principal
            $principalSeleccion = (string)($_POST['imagen_principal'] ?? '');
            $principalTipo = '';
            $principalId = 0;
            $principalNewIndex = -1;

            // Determinar tipo de selección de imagen principal
            if (strpos($principalSeleccion, 'existing_') === 0) {
                $principalTipo = 'existing';
                $principalId = (int)substr($principalSeleccion, strlen('existing_'));
            } elseif (strpos($principalSeleccion, 'new_') === 0) {
                $principalTipo = 'new';
                $principalNewIndex = (int)substr($principalSeleccion, strlen('new_'));
            }

            // Procesar nuevas imágenes subidas
            $rutasNuevas = [];
            if (!empty($_FILES['imagenes']['name'][0])) {
                // Crear directorio de subida si no existe
                $uploadDir = __DIR__ . '/../uploads/vehiculos/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0775, true);

                foreach ($_FILES['imagenes']['name'] as $i => $name) {
                    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) continue;

                    // Crear nombre seguro para el archivo
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
        // Capturar cualquier error durante la actualización
        $_SESSION['errores'] = ['Error al actualizar el vehículo: ' . $e->getMessage()];
        header('Location: index.php?action=editCar&id=' . $idVehiculo);
        exit;
        }
    }
}
