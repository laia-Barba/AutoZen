<?php
namespace Modelo;

use App\Core\Database;
use PDO;

/**
 * Modelo para gestionar las operaciones del carrito de compras
 * Maneja la interacción con la tabla 'carrito' de la base de datos
 */
class CarritoModel
{
    private PDO $db; // Conexión a la base de datos

    /**
     * Constructor: inicializa la conexión a la base de datos
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Agrega un vehículo al carrito de un usuario
     * @param int $idUsuario ID del usuario
     * @param int $idVehiculo ID del vehículo
     * @return bool True si se agregó correctamente, false si ya existía o hubo error
     */
    public function agregarAlCarrito(int $idUsuario, int $idVehiculo): bool
    {
        // INSERT IGNORE evita duplicados gracias a la restricción UNIQUE
        $sql = "INSERT IGNORE INTO carrito (id_usuario, id_vehiculo, reservado) 
                VALUES (:id_usuario, :id_vehiculo, FALSE)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Obtiene todos los vehículos del carrito de un usuario con detalles completos
     * @param int $idUsuario ID del usuario
     * @return array Lista de vehículos con sus detalles y estado de reserva
     */
    public function obtenerVehiculosCarrito(int $idUsuario): array
    {
        // JOIN para obtener todos los detalles del vehículo y el estado de reserva
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km,
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.potencia, v.descripcion, v.imagen,
                       c.reservado
                FROM carrito c
                INNER JOIN vehiculos v ON c.id_vehiculo = v.idVehiculo
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                WHERE c.id_usuario = :id_usuario
                ORDER BY c.id DESC"; // Más recientes primero
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marca un vehículo como reservado en el carrito
     * @param int $idUsuario ID del usuario
     * @param int $idVehiculo ID del vehículo
     * @return bool True si se actualizó correctamente
     */
    public function reservarVehiculo(int $idUsuario, int $idVehiculo): bool
    {
        $sql = "UPDATE carrito 
                SET reservado = TRUE 
                WHERE id_usuario = :id_usuario AND id_vehiculo = :id_vehiculo";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Cancela la reserva de un vehículo (cambia reservado a FALSE)
     * @param int $idUsuario ID del usuario
     * @param int $idVehiculo ID del vehículo
     * @return bool True si se canceló correctamente
     */
    public function cancelarReserva(int $idUsuario, int $idVehiculo): bool
    {
        $sql = "UPDATE carrito 
                SET reservado = FALSE 
                WHERE id_usuario = :id_usuario AND id_vehiculo = :id_vehiculo";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Elimina completamente un vehículo del carrito de un usuario
     * @param int $idUsuario ID del usuario
     * @param int $idVehiculo ID del vehículo
     * @return bool True si se eliminó correctamente
     */
    public function eliminarDelCarrito(int $idUsuario, int $idVehiculo): bool
    {
        $sql = "DELETE FROM carrito 
                WHERE id_usuario = :id_usuario AND id_vehiculo = :id_vehiculo";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Cuenta cuántos vehículos tiene un usuario en su carrito
     * @param int $idUsuario ID del usuario
     * @return int Número de vehículos en el carrito
     */
    public function contarVehiculosCarrito(int $idUsuario): int
    {
        $sql = "SELECT COUNT(*) as total FROM carrito WHERE id_usuario = :id_usuario";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['total']) ? (int)$row['total'] : 0;
    }

    /**
     * Verifica si un vehículo específico ya está en el carrito de un usuario
     * @param int $idUsuario ID del usuario
     * @param int $idVehiculo ID del vehículo
     * @return bool True si ya está en el carrito, false si no
     */
    public function yaEstaEnCarrito(int $idUsuario, int $idVehiculo): bool
    {
        $sql = "SELECT COUNT(*) as total FROM carrito 
                WHERE id_usuario = :id_usuario AND id_vehiculo = :id_vehiculo";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['total']) && (int)$row['total'] > 0;
    }
}
