<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Coches - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #F7931E;
            --dark-color: #2C3E50;
            --light-bg: #F8F9FA;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-top: 80px;
            padding: 40px 0;
        }

        .content-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .btn-secondary-custom {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary-custom:hover {
            background: var(--dark-color);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .car-row {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 16px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .car-thumb {
            width: 120px;
            height: 80px;
            background: #f1f3f5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex: 0 0 auto;
        }

        .car-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            background: #fff;
        }

        .car-title {
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .car-sub {
            color: #6c757d;
            margin: 4px 0 0 0;
            font-size: 0.9rem;
        }

        .car-price {
            font-weight: 800;
            color: var(--dark-color);
            margin: 0;
            text-align: center;
        }

        .car-actions {
            margin-left: auto;
            display: flex;
            gap: 10px;
            flex: 0 0 auto;
        }

        .btn-edit {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: white;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-edit:hover {
            background: rgba(255, 107, 53, 0.08);
            text-decoration: none;
            color: var(--primary-color);
        }

        .btn-delete {
            border: 2px solid #dc3545;
            color: #dc3545;
            background: white;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-delete:hover {
            background: rgba(220, 53, 69, 0.08);
        }

        @media (max-width: 768px) {
            .car-row {
                flex-direction: column;
                align-items: stretch;
            }
            .car-actions {
                margin-left: 0;
                justify-content: flex-end;
            }
            .car-price {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4 d-flex gap-2">
        <a href="index.php?action=admin" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Panel
        </a>
        <a href="index.php?action=addCar" class="btn btn-primary-custom">
            <i class="fas fa-plus-circle me-2"></i>
            Añadir Coche
        </a>
    </div>

    <div class="main-content">
        <div class="container">
            <div class="content-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h1 class="mb-1">Gestionar Coches</h1>
                    <p class="mb-0">Edita o elimina vehículos existentes.</p>
                </div>
            </div>

            <?php if (!empty($_SESSION['mensaje'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['errores'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['errores'] as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; unset($_SESSION['errores']); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (empty($vehiculos)): ?>
                <div class="alert alert-info">No hay vehículos registrados.</div>
            <?php else: ?>
                <?php foreach ($vehiculos as $v): ?>
                    <?php
                        $nombre = trim(($v['marca'] ?? '') . ' ' . ($v['modelo'] ?? ''));
                        $precio = $v['precio'] ?? '';
                        $img = $v['imagen'] ?? '';

                        $imgUrl = '';
                        if (!empty($img)) {
                            // Si es ruta absoluta Windows, convertirla a URL relativa hacia /uploads
                            $pos = stripos($img, 'uploads');
                            if ($pos !== false) {
                                $relative = str_replace('\\', '/', substr($img, $pos));
                                $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                                $imgUrl = $base . '/' . ltrim($relative, '/');
                            } else {
                                $imgUrl = $img;
                            }
                        }
                    ?>

                    <div class="car-row">
                        <div class="car-thumb">
                            <?php if (!empty($imgUrl)): ?>
                                <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($nombre); ?>">
                            <?php else: ?>
                                <div class="text-muted small">Sin imagen</div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow-1">
                            <p class="car-title"><?php echo htmlspecialchars($nombre ?: 'Vehículo #' . (int)$v['idVehiculo']); ?></p>
                            <p class="car-sub">ID: <?php echo (int)$v['idVehiculo']; ?></p>
                        </div>

                        <div class="me-3">
                            <p class="car-price"><?php echo htmlspecialchars($precio); ?> €</p>
                        </div>

                        <div class="car-actions">
                            <a class="btn-edit" href="index.php?action=editCar&id=<?php echo (int)$v['idVehiculo']; ?>">Editar</a>

                            <form method="POST" action="index.php?action=deleteCar" onsubmit="return confirm('¿Seguro que quieres eliminar este coche?');" class="m-0">
                                <input type="hidden" name="idVehiculo" value="<?php echo (int)$v['idVehiculo']; ?>">
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
