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
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.potencia, v.descripcion, v.imagen
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

    public function contarVehiculos(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM vehiculos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['total']) ? (int)$row['total'] : 0;
    }

    public function obtenerVehiculoDetalle(int $idVehiculo): ?array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km,
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.potencia, v.descripcion, v.imagen
                FROM vehiculos v
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                WHERE v.idVehiculo = :idVehiculo";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function obtenerImagenesVehiculoPublic(int $idVehiculo): array
    {
        $sql = "SELECT idImagen, ruta, esPrincipal
                FROM vehiculo_imagenes
                WHERE idVehiculo = :idVehiculo
                ORDER BY esPrincipal DESC, idImagen ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodosVehiculosAdmin(): array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.precio, v.imagen
                FROM vehiculos v
                INNER JOIN marcas m ON v.idMarca = m.idMarca
                INNER JOIN modelo mo ON v.idModelo = mo.idModelo
                ORDER BY v.idVehiculo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarVehiculo(int $idVehiculo): bool
    {
        $sql = "DELETE FROM vehiculos WHERE idVehiculo = :idVehiculo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerVehiculoPorIdAdmin(int $idVehiculo): ?array
    {
        $sql = "SELECT v.idVehiculo, v.idMarca, v.idModelo, v.km, v.combustible, v.color, v.año, v.cambio,
                       v.consumo, v.motor, v.potencia, v.descripcion, v.precio, v.imagen
                FROM vehiculos v
                WHERE v.idVehiculo = :idVehiculo";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function actualizarVehiculo(int $idVehiculo, array $data): bool
    {
        $sql = "UPDATE vehiculos
                SET idMarca = :idMarca,
                    idModelo = :idModelo,
                    km = :km,
                    combustible = :combustible,
                    color = :color,
                    año = :anio,
                    cambio = :cambio,
                    consumo = :consumo,
                    motor = :motor,
                    potencia = :potencia,
                    descripcion = :descripcion,
                    precio = :precio
                WHERE idVehiculo = :idVehiculo";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':idMarca', (int)$data['idMarca'], PDO::PARAM_INT);
        $stmt->bindValue(':idModelo', (int)$data['idModelo'], PDO::PARAM_INT);
        $stmt->bindValue(':km', (int)$data['km'], PDO::PARAM_INT);
        $stmt->bindValue(':combustible', $data['combustible'], PDO::PARAM_STR);
        $stmt->bindValue(':color', $data['color'], PDO::PARAM_STR);
        $stmt->bindValue(':anio', (int)$data['año'], PDO::PARAM_INT);
        $stmt->bindValue(':cambio', $data['cambio'], PDO::PARAM_STR);
        $stmt->bindValue(':consumo', $data['consumo'] !== '' ? $data['consumo'] : null, $data['consumo'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':motor', $data['motor'] ?? null, isset($data['motor']) && $data['motor'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':potencia', (int)$data['potencia'], PDO::PARAM_INT);
        $stmt->bindValue(':descripcion', $data['descripcion'] ?? null, isset($data['descripcion']) && $data['descripcion'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':precio', $data['precio'], PDO::PARAM_STR);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function obtenerImagenesVehiculo(int $idVehiculo): array
    {
        $sql = "SELECT idImagen, ruta, esPrincipal
                FROM vehiculo_imagenes
                WHERE idVehiculo = :idVehiculo
                ORDER BY esPrincipal DESC, idImagen ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarImagenVehiculo(int $idImagen, int $idVehiculo): bool
    {
        $sql = "DELETE FROM vehiculo_imagenes WHERE idImagen = :idImagen AND idVehiculo = :idVehiculo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idImagen', $idImagen, PDO::PARAM_INT);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerRutaImagenPorId(int $idImagen, int $idVehiculo): ?string
    {
        $sql = "SELECT ruta FROM vehiculo_imagenes WHERE idImagen = :idImagen AND idVehiculo = :idVehiculo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idImagen', $idImagen, PDO::PARAM_INT);
        $stmt->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (string)$row['ruta'] : null;
    }

    public function limpiarImagenPrincipal(int $idVehiculo): bool
    {
        $sql1 = "UPDATE vehiculo_imagenes SET esPrincipal = 0 WHERE idVehiculo = :idVehiculo";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = "UPDATE vehiculos SET imagen = :ruta WHERE idVehiculo = :idVehiculo";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bindValue(':ruta', '', PDO::PARAM_STR);
        $stmt2->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        return $stmt2->execute();
    }

    public function setImagenPrincipalPorIdImagen(int $idVehiculo, int $idImagen): bool
    {
        $ruta = $this->obtenerRutaImagenPorId($idImagen, $idVehiculo);
        if ($ruta === null) {
            return false;
        }

        $sql1 = "UPDATE vehiculo_imagenes SET esPrincipal = 0 WHERE idVehiculo = :idVehiculo";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = "UPDATE vehiculo_imagenes SET esPrincipal = 1 WHERE idVehiculo = :idVehiculo AND idImagen = :idImagen";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt2->bindValue(':idImagen', $idImagen, PDO::PARAM_INT);
        $stmt2->execute();

        return $this->updateImagenPrincipal($idVehiculo, $ruta);
    }

    public function setImagenPrincipalPorRuta(int $idVehiculo, string $ruta): bool
    {
        $sql1 = "UPDATE vehiculo_imagenes SET esPrincipal = 0 WHERE idVehiculo = :idVehiculo";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = "UPDATE vehiculo_imagenes SET esPrincipal = 1 WHERE idVehiculo = :idVehiculo AND ruta = :ruta";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bindValue(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        $stmt2->bindValue(':ruta', $ruta, PDO::PARAM_STR);
        $stmt2->execute();

        return $this->updateImagenPrincipal($idVehiculo, $ruta);
    }

    public function obtenerCochesRecientes(int $limite = 8): array
    {
        $sql = "SELECT v.idVehiculo, m.Nombre as marca, mo.Nombre as modelo, v.año, v.precio, v.km, 
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.potencia, v.descripcion, v.imagen
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
                       v.combustible, v.color, v.cambio, v.consumo, v.motor, v.potencia, v.descripcion, v.imagen
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

    /**
     * Inserta un nuevo vehículo en la tabla 'vehiculos'.
     * Espera claves: idMarca, idModelo, km, combustible, color, año, cambio,
     *                consumo, motor, descripcion, precio, imagen (opcional)
     */
    public function crearVehiculo(array $data): int
    {
        $sql = "INSERT INTO vehiculos (
                    idMarca, idModelo, km, combustible, color, año, cambio,
                    consumo, motor, potencia, descripcion, precio, imagen
                ) VALUES (
                    :idMarca, :idModelo, :km, :combustible, :color, :anio, :cambio,
                    :consumo, :motor, :potencia, :descripcion, :precio, :imagen
                )";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':idMarca', (int)$data['idMarca'], PDO::PARAM_INT);
        $stmt->bindValue(':idModelo', (int)$data['idModelo'], PDO::PARAM_INT);
        $stmt->bindValue(':km', (int)$data['km'], PDO::PARAM_INT);
        $stmt->bindValue(':combustible', $data['combustible'], PDO::PARAM_STR);
        $stmt->bindValue(':color', $data['color'], PDO::PARAM_STR);
        $stmt->bindValue(':anio', (int)$data['año'], PDO::PARAM_INT);
        $stmt->bindValue(':cambio', $data['cambio'], PDO::PARAM_STR);
        $stmt->bindValue(':consumo', $data['consumo'] !== '' ? $data['consumo'] : null, $data['consumo'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':motor', $data['motor'] ?? null, isset($data['motor']) && $data['motor'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':potencia', (int)$data['potencia'], PDO::PARAM_INT);
        $stmt->bindValue(':descripcion', $data['descripcion'] ?? null, isset($data['descripcion']) && $data['descripcion'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':precio', $data['precio'], PDO::PARAM_STR);
        // La columna puede ser NOT NULL. Guardamos siempre un string (y luego se actualiza a la imagen principal).
        $stmt->bindValue(':imagen', isset($data['imagen']) ? (string)$data['imagen'] : '', PDO::PARAM_STR);

        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    public function agregarImagenesVehiculo(int $idVehiculo, array $imagenes): int
    {
        if (empty($imagenes)) {
            return 0;
        }

        $sql = "INSERT INTO vehiculo_imagenes (idVehiculo, ruta, esPrincipal) VALUES ";
        $values = [];
        $params = [];
        $i = 0;

        foreach ($imagenes as $img) {
            $values[] = "(:idVehiculo_{$i}, :ruta_{$i}, :esPrincipal_{$i})";
            $params[":idVehiculo_{$i}"] = $idVehiculo;
            $params[":ruta_{$i}"] = $img['ruta'];
            $params[":esPrincipal_{$i}"] = $img['esPrincipal'];
            $i++;
        }

        $sql .= implode(', ', $values);
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            // Los placeholders son :idVehiculo_0 y :esPrincipal_0
            if (strpos($key, ':idVehiculo_') === 0 || strpos($key, ':esPrincipal_') === 0) {
                $stmt->bindValue($key, (int)$val, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, (string)$val, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateImagenPrincipal(int $idVehiculo, string $rutaImagen): bool
    {
        $sql = "UPDATE vehiculos SET imagen = :ruta WHERE idVehiculo = :idVehiculo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ruta', $rutaImagen, PDO::PARAM_STR);
        $stmt->bindParam(':idVehiculo', $idVehiculo, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
