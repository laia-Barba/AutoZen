<?php
namespace Modelo;

use App\Core\Database;
use PDO;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function registrarUsuario(string $nombre, string $correo, string $contraseña, string $clave, ?string $telefono = null): bool
    {
        // Check if email already exists
        if ($this->buscarPorCorreo($correo)) {
            return false;
        }

        $sql = "INSERT INTO usuarios (Nombre, Correo, `Contraseña`, Telefono, clave, isAdmin) 
                VALUES (:nombre, :correo, :contrasena, :telefono, :clave, 0)";
        
        $stmt = $this->db->prepare($sql);
        
        $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);
        $hashedClave = password_hash($clave, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':clave', $hashedClave, PDO::PARAM_STR);
        
        // Handle telefono parameter properly - bind as null if empty
        if ($telefono === null || $telefono === '') {
            $stmt->bindValue(':telefono', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }

    public function login(string $correo, string $contraseña): ?array
    {
        error_log("=== LOGIN ATTEMPT ===");
        error_log("Email: " . $correo);
        error_log("Password length: " . strlen($contraseña));
        
        $usuario = $this->buscarPorCorreo($correo);
        
        // Debug: Check if user was found
        if (!$usuario) {
            error_log("Usuario no encontrado: " . $correo);
            return null;
        }
        
        error_log("Usuario encontrado en BD:");
        error_log("ID: " . $usuario['idUsuario']);
        error_log("Nombre: " . $usuario['Nombre']);
        error_log("Hash almacenado: " . $usuario['Contraseña']);
        
        // Test password verification
        $verifyResult = password_verify($contraseña, $usuario['Contraseña']);
        error_log("Password verify result: " . ($verifyResult ? 'SUCCESS' : 'FAILED'));
        
        // Additional password info
        $passwordInfo = password_get_info($usuario['Contraseña']);
        error_log("Password info: " . print_r($passwordInfo, true));
        
        if ($verifyResult) {
            // Remove password from returned data
            unset($usuario['Contraseña']);
            error_log("Login exitoso para: " . $usuario['Nombre']);
            return $usuario;
        }
        
        error_log("Login fallido para: " . $correo);
        return null;
    }

    public function buscarPorCorreo(string $correo): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE Correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    public function existeCorreoEnOtroUsuario(string $correo, int $idUsuarioActual): bool
    {
        $sql = "SELECT idUsuario FROM usuarios WHERE Correo = :correo AND idUsuario <> :idUsuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':idUsuario', $idUsuarioActual, PDO::PARAM_INT);
        $stmt->execute();

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validarCorreoYClave(string $correo, string $clave): ?int
    {
        $sql = "SELECT idUsuario, clave FROM usuarios WHERE Correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || !isset($row['idUsuario'], $row['clave'])) {
            return null;
        }

        $hashClave = (string)$row['clave'];
        if (!password_verify($clave, $hashClave)) {
            return null;
        }

        return (int)$row['idUsuario'];
    }

    public function buscarPorId(int $idUsuario): ?array
    {
        $sql = "SELECT idUsuario, Nombre, Correo, Telefono, isAdmin FROM usuarios WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    public function obtenerHashContrasenaPorId(int $idUsuario): ?string
    {
        $sql = "SELECT `Contraseña` AS contrasena FROM usuarios WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['contrasena']) ? (string)$row['contrasena'] : null;
    }

    public function actualizarUsuario(int $idUsuario, string $nombre, string $correo, ?string $telefono = null): bool
    {
        $sql = "UPDATE usuarios SET Nombre = :nombre, Correo = :correo, Telefono = :telefono WHERE idUsuario = :idUsuario";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);

        if ($telefono === null || $telefono === '') {
            $stmt->bindValue(':telefono', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }

    public function cambiarContraseña(int $idUsuario, string $nuevaContraseña): bool
    {
        $sql = "UPDATE usuarios SET `Contraseña` = :contrasena WHERE idUsuario = :idUsuario";
        $stmt = $this->db->prepare($sql);
        
        $hashedPassword = password_hash($nuevaContraseña, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':contrasena', $hashedPassword, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
}
