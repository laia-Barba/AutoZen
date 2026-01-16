<?php
namespace Controlador;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Clase para gestionar la conexión a la base de datos
 * Implementa el patrón Singleton para una única conexión
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Obtiene la instancia de la conexión a la base de datos
     * @return PDO Instancia de la conexión
     * @throws RuntimeException Si hay error de conexión
     */
    public static function getInstance(): PDO
    {
        // Si ya existe una instancia, retornarla
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }

        // Configuración de la base de datos
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $name = getenv('DB_NAME') ?: 'autozen';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $port = getenv('DB_PORT') ?: '3306';
        $charset = 'utf8mb4';

        // Validar que el nombre de la base de datos esté configurado
        if ($name === '') {
            throw new RuntimeException('DB_NAME no está configurado. Define las variables de entorno (DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_PORT).');
        }

        // Crear DSN para la conexión
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        // Opciones de PDO
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Crear nueva instancia de PDO
            self::$instance = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Lanzar excepción con mensaje de error
            throw new RuntimeException('Error de conexión a la base de datos: ' . $e->getMessage(), (int)$e->getCode());
        }

        return self::$instance;
    }
}
