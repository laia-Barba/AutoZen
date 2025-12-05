<?php
namespace Controlador;

use Modelo\UserModel;

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function mostrarLogin(): void
    {
        if ($this->estaLogueado()) {
            header('Location: index.php?action=perfil');
            exit;
        }
        
        require_once __DIR__ . '/../Vista/auth/login.php';
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
            header('Location: index.php?action=registro');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contraseña = $_POST['contraseña'] ?? '';
        $confirmarContraseña = $_POST['confirmar_contraseña'] ?? '';
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

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=registro');
            exit;
        }

        // Intentar registrar usuario
        if ($this->userModel->registrarUsuario($nombre, $correo, $contraseña, $telefono)) {
            $_SESSION['mensaje'] = 'Usuario registrado correctamente. Ahora puedes iniciar sesión.';
            header('Location: index.php?action=login');
            exit;
        } else {
            $_SESSION['errores'] = ['El correo ya está registrado'];
            $_SESSION['datos_formulario'] = $_POST;
            header('Location: index.php?action=registro');
            exit;
        }
    }

    public function login(): void
    {
        error_log("Login method called");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not POST request, redirecting to login");
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
            
            // Redirigir a la página principal para todos los usuarios
            header('Location: index.php');
            exit;
        } else {
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
