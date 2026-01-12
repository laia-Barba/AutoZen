<?php
namespace Modelo;

use App\Core\Database;
use PDO;

class CarritoModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function agregarAlCarrito(int $idUsuario, int $idVehiculo): bool
    {
        $sql = "INSERT IGNORE INTO carrito (id_usuario, id_vehiculo, reservado) 
                VALUES (:id_usuario, :id_vehiculo, FALSE)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function obtenerVehiculosCarrito(int $idUsuario): array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km,
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.potencia, v.descripcion, v.imagen,
                       c.reservado
                FROM carrito c
                INNER JOIN vehiculos v ON c.id_vehiculo = v.idVehiculo
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                WHERE c.id_usuario = :id_usuario
                ORDER BY c.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function eliminarDelCarrito(int $idUsuario, int $idVehiculo): bool
    {
        $sql = "DELETE FROM carrito 
                WHERE id_usuario = :id_usuario AND id_vehiculo = :id_vehiculo";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_vehiculo', $idVehiculo, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function contarVehiculosCarrito(int $idUsuario): int
    {
        $sql = "SELECT COUNT(*) as total FROM carrito WHERE id_usuario = :id_usuario";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['total']) ? (int)$row['total'] : 0;
    }

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
