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
        // Datos de ejemplo mientras configuramos la BD
        return [
            [
                'id' => 1,
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'anio' => 2021,
                'precio' => 18990,
                'kilometraje' => 25000,
                'combustible' => 'híbrido',
                'imagen' => 'toyota_corolla.jpg',
                'destacado' => true
            ],
            [
                'id' => 2,
                'marca' => 'Volkswagen',
                'modelo' => 'Golf',
                'anio' => 2020,
                'precio' => 16500,
                'kilometraje' => 45000,
                'combustible' => 'diesel',
                'imagen' => 'vw_golf.jpg',
                'destacado' => true
            ],
            [
                'id' => 3,
                'marca' => 'BMW',
                'modelo' => 'Serie 3',
                'anio' => 2022,
                'precio' => 28500,
                'kilometraje' => 18000,
                'combustible' => 'diesel',
                'imagen' => 'bmw_serie3.jpg',
                'destacado' => true
            ]
        ];
    }

    public function obtenerCochesRecientes(int $limite = 8): array
    {
        // Datos de ejemplo
        return [
            [
                'id' => 4,
                'marca' => 'Ford',
                'modelo' => 'Focus',
                'anio' => 2019,
                'precio' => 14200,
                'kilometraje' => 62000,
                'combustible' => 'gasolina',
                'imagen' => 'ford_focus.jpg'
            ],
            [
                'id' => 5,
                'marca' => 'Mercedes',
                'modelo' => 'Clase A',
                'anio' => 2023,
                'precio' => 32450,
                'kilometraje' => 8000,
                'combustible' => 'gasolina',
                'imagen' => 'mercedes_claseA.jpg'
            ],
            [
                'id' => 6,
                'marca' => 'Audi',
                'modelo' => 'A3',
                'anio' => 2021,
                'precio' => 22100,
                'kilometraje' => 35000,
                'combustible' => 'diesel',
                'imagen' => 'audi_a3.jpg'
            ],
            [
                'id' => 7,
                'marca' => 'Seat',
                'modelo' => 'León',
                'anio' => 2022,
                'precio' => 18900,
                'kilometraje' => 22000,
                'combustible' => 'gasolina',
                'imagen' => 'seat_leon.jpg'
            ]
        ];
    }

    public function obtenerMarcas(): array
    {
        // Datos de ejemplo
        return [
            ['id' => 1, 'nombre' => 'Toyota'],
            ['id' => 2, 'nombre' => 'Volkswagen'],
            ['id' => 3, 'nombre' => 'Ford'],
            ['id' => 4, 'nombre' => 'BMW'],
            ['id' => 5, 'nombre' => 'Mercedes-Benz'],
            ['id' => 6, 'nombre' => 'Audi'],
            ['id' => 7, 'nombre' => 'Seat'],
            ['id' => 8, 'nombre' => 'Renault'],
            ['id' => 9, 'nombre' => 'Opel'],
            ['id' => 10, 'nombre' => 'Peugeot']
        ];
    }

    public function obtenerEstadisticas(): array
    {
        // Datos de ejemplo
        return [
            'total_coches' => 156,
            'total_marcas' => 15,
            'precio_promedio' => 18750.00
        ];
    }

    public function buscarCoches(array $filtros = []): array
    {
        // Simulación de búsqueda - devuelve todos los coches por ahora
        return array_merge($this->obtenerCochesDestacados(), $this->obtenerCochesRecientes());
    }
}
