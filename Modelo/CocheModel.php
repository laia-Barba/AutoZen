<?php
namespace Modelo;

use App\Core\Database;
use PDO;

class CocheModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function obtenerCochesDestacados(int $limite = 6): array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km, 
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.descripcion
                FROM vehiculos v
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                ORDER BY v.precio DESC
                LIMIT :limite";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCochesRecientes(int $limite = 8): array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km, 
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.descripcion
                FROM vehiculos v
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                ORDER BY v.año DESC, v.idVehiculo DESC
                LIMIT :limite";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMarcas(): array
    {
        $sql = "SELECT idMarca as id, Nombre as nombre FROM marcas ORDER BY Nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerModelosPorMarca(int $idMarca): array
    {
        $sql = "SELECT idModelo as id, Nombre as nombre FROM modelo WHERE idMarca = :idMarca ORDER BY Nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idMarca', $idMarca, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticas(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_coches,
                    (SELECT COUNT(*) FROM marcas) as total_marcas,
                    AVG(precio) as precio_promedio
                FROM vehiculos";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'total_coches' => (int)$result['total_coches'],
            'total_marcas' => (int)$result['total_marcas'],
            'precio_promedio' => (float)$result['precio_promedio']
        ];
    }

    public function buscarCoches(array $filtros = []): array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km, 
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.descripcion
                FROM vehiculos v
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filtros['marca'])) {
            $sql .= " AND v.idMarca = :marca";
            $params[':marca'] = $filtros['marca'];
        }
        
        if (!empty($filtros['modelo'])) {
            $sql .= " AND v.idModelo = :modelo";
            $params[':modelo'] = $filtros['modelo'];
        }
        
        if (!empty($filtros['combustible'])) {
            $sql .= " AND v.combustible = :combustible";
            $params[':combustible'] = $filtros['combustible'];
        }
        
        if (!empty($filtros['precio_min'])) {
            $sql .= " AND v.precio >= :precio_min";
            $params[':precio_min'] = $filtros['precio_min'];
        }
        
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND v.precio <= :precio_max";
            $params[':precio_max'] = $filtros['precio_max'];
        }
        
        if (!empty($filtros['año_min'])) {
            $sql .= " AND v.año >= :año_min";
            $params[':año_min'] = $filtros['año_min'];
        }
        
        if (!empty($filtros['año_max'])) {
            $sql .= " AND v.año <= :año_max";
            $params[':año_max'] = $filtros['año_max'];
        }
        
        $sql .= " ORDER BY v.precio DESC";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $param => $value) {
            if (is_numeric($value)) {
                $stmt->bindValue($param, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($param, $value, PDO::PARAM_STR);
            }
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
