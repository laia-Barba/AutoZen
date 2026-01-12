-- Crear la tabla carrito para gestionar los vehículos en el carrito de los usuarios
CREATE TABLE IF NOT EXISTS carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT NOT NULL,
    id_usuario INT NOT NULL,
    reservado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(idVehiculo) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE,
    UNIQUE KEY unique_vehiculo_usuario (id_vehiculo, id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
