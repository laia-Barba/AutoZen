<?php
namespace Controlador;

use Modelo\UserModel;
use Modelo\CocheModel;

class AuthController
{
    private UserModel $userModel;
    private CocheModel $cocheModel;

    private function esAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower((string)$_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function responderJson(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->cocheModel = new CocheModel();
    }

    public function mostrarLogin(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }
        
        require_once __DIR__ . '/../Vista/auth/login.php';
    }

    public function mostrarRecuperarContrasenaPaso1(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }

        unset($_SESSION['password_reset_user_id']);
        $pageTitle = 'Recuperar Contraseña - Paso 1 - AutoZen';
        $backHref = 'index.php?action=login';
        $backText = 'Volver al Login';
        $headerIcon = 'fas fa-unlock-alt';
        $headerTitle = 'Recuperar Contraseña';
        $formAction = 'index.php?action=forgotPasswordValidate';
        $correoValue = $_SESSION['datos_formulario']['correo'] ?? '';
        $correoReadonly = false;
        $claveValue = $_SESSION['datos_formulario']['clave'] ?? '';
        $hintText = 'La palabra clave se define al registrarte.';

        require_once __DIR__ . '/../Vista/auth/password_paso1.php';
    }

    public function validarClaveRecuperarContrasena(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=forgotPassword');
            exit;
        }

        $correo = trim($_POST['correo'] ?? '');
        $clave = trim($_POST['clave'] ?? '');

        $errores = [];
        if ($correo === '') $errores[] = 'El correo es obligatorio';
        if ($clave === '') $errores[] = 'La palabra clave es obligatoria';

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=forgotPassword');
            exit;
        }

        $idUsuario = $this->userModel->validarCorreoYClave($correo, $clave);
        if (!$idUsuario) {
            $_SESSION['errores'] = ['Correo o palabra clave incorrectos'];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=forgotPassword');
            exit;
        }

        $_SESSION['password_reset_user_id'] = $idUsuario;
        header('Location: index.php?action=forgotPasswordNew');
        exit;
    }

    public function mostrarRecuperarContrasenaPaso2(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }

        $idTmp = (int)($_SESSION['password_reset_user_id'] ?? 0);
        if ($idTmp <= 0) {
            header('Location: index.php?action=forgotPassword');
            exit;
        }

        $pageTitle = 'Recuperar Contraseña - Paso 2 - AutoZen';
        $backHref = 'index.php?action=forgotPassword';
        $backText = 'Volver al Paso 1';
        $headerIcon = 'fas fa-lock';
        $headerTitle = 'Nueva Contraseña';
        $formAction = 'index.php?action=forgotPasswordSave';

        require_once __DIR__ . '/../Vista/auth/password_paso2.php';
    }

    public function guardarRecuperarContrasena(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=forgotPasswordNew');
            exit;
        }

        $idTmp = (int)($_SESSION['password_reset_user_id'] ?? 0);
        if ($idTmp <= 0) {
            header('Location: index.php?action=forgotPassword');
            exit;
        }

        $nueva = (string)($_POST['nueva_contraseña'] ?? '');
        $confirmar = (string)($_POST['confirmar_nueva_contraseña'] ?? '');

        $errores = [];
        if ($nueva === '') $errores[] = 'La nueva contraseña es obligatoria';
        if (strlen($nueva) < 6) $errores[] = 'La nueva contraseña debe tener al menos 6 caracteres';
        if ($nueva !== $confirmar) $errores[] = 'Las contraseñas no coinciden';

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: index.php?action=forgotPasswordNew');
            exit;
        }

        $hashActual = $this->userModel->obtenerHashContrasenaPorId($idTmp);
        if ($hashActual && password_verify($nueva, $hashActual)) {
            $_SESSION['errores'] = ['La contraseña no puede ser igual que la anterior'];
            header('Location: index.php?action=forgotPasswordNew');
            exit;
        }

        $ok = $this->userModel->cambiarContraseña($idTmp, $nueva);
        unset($_SESSION['password_reset_user_id']);

        if ($ok) {
            $_SESSION['mensaje'] = 'Contraseña actualizada. Ya puedes iniciar sesión.';
            header('Location: index.php?action=login');
            exit;
        }

        $_SESSION['errores'] = ['No se pudo actualizar la contraseña'];
        header('Location: index.php?action=forgotPasswordNew');
        exit;
    }

    public function mostrarRegistro(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }
        
        require_once __DIR__ . '/../Vista/auth/registro.php';
    }

    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->esAjax()) {
                $this->responderJson(['ok' => false, 'errors' => ['Método no permitido']], 405);
            }
            header('Location: index.php?action=registro');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contraseña = $_POST['contraseña'] ?? '';
        $confirmarContraseña = $_POST['confirmar_contraseña'] ?? '';
        $clave = trim($_POST['clave'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        $errores = [];

        // Validaciones
        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio';
        }

        if (empty($correo)) {
            $errores[] = 'El correo es obligatorio';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no es válido';
        }

        if (empty($contraseña)) {
            $errores[] = 'La contraseña es obligatoria';
        } elseif (strlen($contraseña) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres';
        } elseif ($contraseña !== $confirmarContraseña) {
            $errores[] = 'Las contraseñas no coinciden';
        }

        if (empty($clave)) {
            $errores[] = 'La palabra clave es obligatoria';
        } elseif (strlen($clave) < 4) {
            $errores[] = 'La palabra clave debe tener al menos 4 caracteres';
        }

        if (!empty($errores)) {
            if ($this->esAjax()) {
                $this->responderJson(['ok' => false, 'errors' => $errores], 422);
            }
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=registro');
            exit;
        }

        // Intentar registrar usuario
        if ($this->userModel->registrarUsuario($nombre, $correo, $contraseña, $clave, $telefono)) {
            if ($this->esAjax()) {
                $this->responderJson([
                    'ok' => true,
                    'redirect' => 'index.php?action=login'
                ]);
            }
            $_SESSION['mensaje'] = 'Usuario registrado correctamente. Ahora puedes iniciar sesión.';
            header('Location: index.php?action=login');
            exit;
        } else {
            if ($this->esAjax()) {
                $this->responderJson(['ok' => false, 'errors' => ['El correo ya está registrado']], 409);
            }
            $_SESSION['errores'] = ['El correo ya está registrado'];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=registro');
            exit;
        }
    }

    public function checkEmail(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->responderJson(['ok' => false, 'errors' => ['Método no permitido']], 405);
        }

        $correo = trim((string)($_GET['correo'] ?? ''));
        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->responderJson(['ok' => true, 'exists' => false]);
        }

        $usuario = $this->userModel->buscarPorCorreo($correo);
        $this->responderJson(['ok' => true, 'exists' => (bool)$usuario]);
    }

    public function login(): void
    {
        error_log("Login method called");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not POST request, redirecting to login");
            if ($this->esAjax()) {
                $this->responderJson(['ok' => false, 'errors' => ['Método no permitido']], 405);
            }
            header('Location: index.php?action=login');
            exit;
        }

        $correo = trim($_POST['correo'] ?? '');
        $contraseña = $_POST['contraseña'] ?? '';
        $recordar = isset($_POST['recordar']);
        
        error_log("Login attempt - Email: " . $correo);

        $errores = [];

        if (empty($correo)) {
            $errores[] = 'El correo es obligatorio';
        }

        if (empty($contraseña)) {
            $errores[] = 'La contraseña es obligatoria';
        }

        if (!empty($errores)) {
            if ($this->esAjax()) {
                $this->responderJson(['ok' => false, 'errors' => $errores], 422);
            }
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=login');
            exit;
        }

        error_log("Calling UserModel login");
        $usuario = $this->userModel->login($correo, $contraseña);
        
        error_log("UserModel login result: " . ($usuario ? "success" : "failed"));

        if ($usuario) {
            // Iniciar sesión
            session_regenerate_id(true);
            $_SESSION['usuario'] = $usuario;
            $_SESSION['logueado'] = true;

            // Recordar usuario
            if ($recordar) {
                setcookie('recordar_correo', $correo, time() + (30 * 24 * 60 * 60), '/');
            } else {
                setcookie('recordar_correo', '', time() - 3600, '/');
            }

            $_SESSION['mensaje'] = '¡Bienvenido ' . htmlspecialchars($usuario['Nombre']) . '!';

            if ($this->esAjax()) {
                $this->responderJson([
                    'ok' => true,
                    'redirect' => 'index.php'
                ]);
            }

            // Redirigir a la página principal para todos los usuarios
            header('Location: index.php');
            exit;
        } else {
            if ($this->esAjax()) {
                $this->responderJson(['ok' => false, 'errors' => ['Correo o contraseña incorrectos']], 401);
            }
            $_SESSION['errores'] = ['Correo o contraseña incorrectos'];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function logout(): void
    {
        session_destroy();
        setcookie('recordar_correo', '', time() - 3600, '/');
        header('Location: index.php');
        exit;
    }

    public function mostrarPerfil(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuario = $this->userModel->buscarPorId($_SESSION['usuario']['idUsuario']);
        require_once __DIR__ . '/../Vista/auth/perfil.php';
    }

    public function mostrarEditarPerfil(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuario = $this->userModel->buscarPorId($_SESSION['usuario']['idUsuario']);
        require_once __DIR__ . '/../Vista/auth/editar_perfil.php';
    }

    public function guardarEditarPerfil(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=editProfile');
            exit;
        }

        $idUsuario = (int)($_SESSION['usuario']['idUsuario'] ?? 0);
        if ($idUsuario <= 0) {
            header('Location: index.php?action=login');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        $errores = [];
        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio';
        }
        if ($correo === '') {
            $errores[] = 'El correo es obligatorio';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no es válido';
        } elseif ($this->userModel->existeCorreoEnOtroUsuario($correo, $idUsuario)) {
            $errores[] = 'El correo ya está registrado';
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=editProfile');
            exit;
        }

        $telefonoParam = $telefono === '' ? null : $telefono;
        $ok = $this->userModel->actualizarUsuario($idUsuario, $nombre, $correo, $telefonoParam);

        if ($ok) {
            $_SESSION['usuario']['Nombre'] = $nombre;
            $_SESSION['usuario']['Correo'] = $correo;
            if ($telefonoParam !== null) {
                $_SESSION['usuario']['Telefono'] = $telefonoParam;
            } else {
                unset($_SESSION['usuario']['Telefono']);
            }

            unset($_SESSION['datos_formulario']);
            $_SESSION['mensaje'] = 'Perfil actualizado correctamente.';
            header('Location: index.php?action=perfil');
            exit;
        }

        $_SESSION['errores'] = ['No se pudo actualizar el perfil'];
        $_SESSION['datos_formulario'] = $_POST;
        header('Location: index.php?action=editProfile');
        exit;
    }

    public function mostrarCarrito(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuario = $this->userModel->buscarPorId((int)$_SESSION['usuario']['idUsuario']);
        $correo = trim((string)($_SESSION['usuario']['Correo'] ?? ($usuario['Correo'] ?? '')));
        $telefono = trim((string)($_SESSION['usuario']['Telefono'] ?? ($usuario['Telefono'] ?? '')));
        $telefonoDigits = preg_replace('/\D+/', '', $telefono);

        $needsContact = ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || !preg_match('/^\d{9}$/', (string)$telefonoDigits));

        $vehiculosCarrito = [];
        if (!$needsContact) {
            $ids = isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) ? array_map('intval', $_SESSION['carrito']) : [];
            $ids = array_values(array_unique(array_filter($ids, fn($id) => $id > 0)));
            foreach ($ids as $idVehiculo) {
                $v = $this->cocheModel->obtenerVehiculoDetalle((int)$idVehiculo);
                if ($v) {
                    $vehiculosCarrito[] = $v;
                }
            }
        }
        require_once __DIR__ . '/../Vista/auth/carrito.php';
    }

    public function guardarCarrito(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=carrito');
            exit;
        }

        $idUsuario = (int)($_SESSION['usuario']['idUsuario'] ?? 0);
        if ($idUsuario <= 0) {
            header('Location: index.php?action=login');
            exit;
        }

        $correo = trim((string)($_POST['correo'] ?? ''));
        $telefono = trim((string)($_POST['telefono'] ?? ''));

        $telefonoDigits = preg_replace('/\D+/', '', $telefono);

        $errores = [];
        if ($correo === '') {
            $errores[] = 'El correo es obligatorio';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no es válido';
        } elseif ($this->userModel->existeCorreoEnOtroUsuario($correo, $idUsuario)) {
            $errores[] = 'El correo ya está registrado';
        }

        if ($telefono === '') {
            $errores[] = 'El teléfono es obligatorio para poder reservar';
        } elseif (!preg_match('/^\d{9}$/', (string)$telefonoDigits)) {
            $errores[] = 'El teléfono debe tener exactamente 9 números';
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = [
                'correo' => $correo,
                'telefono' => $telefono,
            ];
            header('Location: index.php?action=carrito');
            exit;
        }

        $nombreActual = (string)($_SESSION['usuario']['Nombre'] ?? '');
        $telefonoParam = $telefonoDigits === '' ? null : (string)$telefonoDigits;

        $ok = $this->userModel->actualizarUsuario($idUsuario, $nombreActual, $correo, $telefonoParam);
        if ($ok) {
            $_SESSION['usuario']['Correo'] = $correo;
            $_SESSION['usuario']['Telefono'] = (string)$telefonoDigits;
            unset($_SESSION['datos_formulario']);
            $_SESSION['mensaje'] = 'Datos de contacto guardados correctamente.';
            header('Location: index.php?action=carrito');
            exit;
        }

        $_SESSION['errores'] = ['No se pudieron guardar los datos de contacto'];
        $_SESSION['datos_formulario'] = [
            'correo' => $correo,
            'telefono' => $telefono,
        ];
        header('Location: index.php?action=carrito');
        exit;
    }

    public function cartAdd(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderJson(['ok' => false, 'errors' => ['Método no permitido']], 405);
        }

        if (!$this->estaLogueado()) {
            $this->responderJson([
                'ok' => false,
                'errors' => ['Debes iniciar sesión para añadir al carrito'],
            ], 401);
        }

        $idVehiculo = (int)($_POST['idVehiculo'] ?? 0);
        if ($idVehiculo <= 0) {
            $this->responderJson(['ok' => false, 'errors' => ['Vehículo inválido']], 422);
        }

        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $cart = array_map('intval', (array)$_SESSION['carrito']);
        if (!in_array($idVehiculo, $cart, true)) {
            $cart[] = $idVehiculo;
        }

        $_SESSION['carrito'] = $cart;
        $this->responderJson([
            'ok' => true,
            'count' => count($cart),
        ]);
    }

    public function mostrarCambioContrasenaPaso1(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        unset($_SESSION['password_change_user_id']);
        $usuario = $this->userModel->buscarPorId($_SESSION['usuario']['idUsuario']);

        $pageTitle = 'Cambiar Contraseña - Paso 1 - AutoZen';
        $backHref = 'index.php?action=perfil';
        $backText = 'Volver al Perfil';
        $headerIcon = 'fas fa-key';
        $headerTitle = 'Cambiar Contraseña';
        $formAction = 'index.php?action=validatePasswordKey';
        $correoValue = $_SESSION['datos_formulario']['correo'] ?? ($usuario['Correo'] ?? '');
        $correoReadonly = true;
        $claveValue = $_SESSION['datos_formulario']['clave'] ?? '';
        $hintText = 'Es la clave que tienes guardada en tu cuenta (campo <b>clave</b> de la tabla usuarios).';

        require_once __DIR__ . '/../Vista/auth/password_paso1.php';
    }

    public function validarClaveCambioContrasena(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=changePassword');
            exit;
        }

        $correo = trim($_POST['correo'] ?? '');
        $clave = trim($_POST['clave'] ?? '');

        $errores = [];
        if ($correo === '') $errores[] = 'El correo es obligatorio';
        if ($clave === '') $errores[] = 'La palabra clave es obligatoria';

        $correoSesion = (string)($_SESSION['usuario']['Correo'] ?? '');
        if ($correoSesion !== '' && $correo !== '' && strcasecmp($correo, $correoSesion) !== 0) {
            $errores[] = 'El correo no coincide con tu cuenta';
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=changePassword');
            exit;
        }

        $idUsuario = $this->userModel->validarCorreoYClave($correo, $clave);
        if (!$idUsuario || $idUsuario !== (int)$_SESSION['usuario']['idUsuario']) {
            $_SESSION['errores'] = ['Correo o palabra clave incorrectos'];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=changePassword');
            exit;
        }

        $_SESSION['password_change_user_id'] = $idUsuario;
        header('Location: index.php?action=changePasswordNew');
        exit;
    }

    public function mostrarCambioContrasenaPaso2(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        $idTmp = (int)($_SESSION['password_change_user_id'] ?? 0);
        if ($idTmp <= 0 || $idTmp !== (int)$_SESSION['usuario']['idUsuario']) {
            header('Location: index.php?action=changePassword');
            exit;
        }

        $usuario = $this->userModel->buscarPorId($_SESSION['usuario']['idUsuario']);

        $pageTitle = 'Cambiar Contraseña - Paso 2 - AutoZen';
        $backHref = 'index.php?action=changePassword';
        $backText = 'Volver al Paso 1';
        $headerIcon = 'fas fa-lock';
        $headerTitle = 'Nueva Contraseña';
        $formAction = 'index.php?action=changePasswordSave';

        require_once __DIR__ . '/../Vista/auth/password_paso2.php';
    }

    public function guardarNuevaContrasena(): void
    {
        if (!$this->estaLogueado()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=changePasswordNew');
            exit;
        }

        $idTmp = (int)($_SESSION['password_change_user_id'] ?? 0);
        if ($idTmp <= 0 || $idTmp !== (int)$_SESSION['usuario']['idUsuario']) {
            header('Location: index.php?action=changePassword');
            exit;
        }

        $nueva = (string)($_POST['nueva_contraseña'] ?? '');
        $confirmar = (string)($_POST['confirmar_nueva_contraseña'] ?? '');

        $errores = [];
        if ($nueva === '') $errores[] = 'La nueva contraseña es obligatoria';
        if (strlen($nueva) < 6) $errores[] = 'La nueva contraseña debe tener al menos 6 caracteres';
        if ($nueva !== $confirmar) $errores[] = 'Las contraseñas no coinciden';

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: index.php?action=changePasswordNew');
            exit;
        }

        $hashActual = $this->userModel->obtenerHashContrasenaPorId($idTmp);
        if ($hashActual && password_verify($nueva, $hashActual)) {
            $_SESSION['errores'] = ['La contraseña no puede ser igual que la anterior'];
            header('Location: index.php?action=changePasswordNew');
            exit;
        }

        $ok = $this->userModel->cambiarContraseña($idTmp, $nueva);
        unset($_SESSION['password_change_user_id']);

        if ($ok) {
            $_SESSION['mensaje'] = 'Contraseña actualizada correctamente.';
            header('Location: index.php?action=perfil');
            exit;
        }

        $_SESSION['errores'] = ['No se pudo actualizar la contraseña'];
        header('Location: index.php?action=changePasswordNew');
        exit;
    }

    public function estaLogueado(): bool
    {
        return isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
    }

    public function getUsuarioActual(): ?array
    {
        return $this->estaLogueado() ? $_SESSION['usuario'] : null;
    }

    public function esAdmin(): bool
    {
        $usuario = $this->getUsuarioActual();
        return $usuario && $usuario['isAdmin'] == 1;
    }
}
