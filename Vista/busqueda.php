<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #004E89;
            --accent-color: #FFC947;
            --dark-color: #1A1A2E;
            --light-bg: #F8F9FA;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #FF6B35 0%, #FFC947 100%);
            --gradient-3: linear-gradient(135deg, #004E89 0%, #009FFD 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
        }

        /* Navegación */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 40px 0;
        }

        .search-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .results-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .results-count {
            color: #6c757d;
            font-size: 1.1rem;
        }

        /* Filters Sidebar */
        .filters-sidebar {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 120px;
        }

        .filter-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .filter-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 10px;
            display: block;
        }

        .price-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-btn {
            background: var(--gradient-2);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }

        /* Car Cards */
        .car-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .car-image-container {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .car-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .car-card:hover .car-image {
            transform: scale(1.1);
        }

        .car-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gradient-2);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .car-details {
            padding: 25px;
        }

        .car-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .car-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 0.95rem;
        }

        .spec-item i {
            color: var(--primary-color);
        }

        .car-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 20px 0;
        }

        .car-actions {
            display: flex;
            gap: 15px;
        }

        .btn-primary-custom {
            background: var(--gradient-3);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            flex: 1;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 78, 137, 0.3);
        }

        .btn-secondary-custom {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .no-results-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .no-results-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .no-results-text {
            color: #6c757d;
            margin-bottom: 30px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filters-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 30px;
            }
            
            .car-specs {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .car-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-car"></i> AutoZen
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#coches">Coches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destacados">Destacados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Search Header -->
            <div class="search-header">
                <h1 class="results-title">Resultados de Búsqueda</h1>
                <p class="results-count">
                    <?php 
                    $count = count($resultados);
                    echo $count . ' ' . ($count == 1 ? 'coche encontrado' : 'coches encontrados');
                    ?>
                </p>
            </div>

            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3">
                    <div class="filters-sidebar">
                        <h3 class="filter-title">Filtros</h3>
                        
                        <form action="index.php" method="GET">
                            <input type="hidden" name="action" value="buscar">
                            
                            <div class="filter-group">
                                <label class="filter-label">Marca</label>
                                <select class="form-select" name="marca">
                                    <option value="">Todas las marcas</option>
                                    <?php foreach ($marcas as $marca): ?>
                                        <option value="<?php echo $marca['id']; ?>" 
                                            <?php echo (isset($_GET['marca']) && $_GET['marca'] == $marca['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($marca['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Precio</label>
                                <div class="price-range">
                                    <input type="number" class="form-control" name="precio_min" 
                                           placeholder="Mín" value="<?php echo htmlspecialchars($_GET['precio_min'] ?? ''); ?>">
                                    <span>-</span>
                                    <input type="number" class="form-control" name="precio_max" 
                                           placeholder="Máx" value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Kilometraje Máximo</label>
                                <input type="number" class="form-control" name="kilometraje_max" 
                                       placeholder="Ej: 100000" value="<?php echo htmlspecialchars($_GET['kilometraje_max'] ?? ''); ?>">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Año Mínimo</label>
                                <input type="number" class="form-control" name="anio_min" 
                                       placeholder="Ej: 2015" value="<?php echo htmlspecialchars($_GET['anio_min'] ?? ''); ?>">
                            </div>

                            <button type="submit" class="filter-btn">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-9">
                    <?php if (empty($resultados)): ?>
                        <div class="no-results">
                            <div class="no-results-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3 class="no-results-title">No se encontraron resultados</h3>
                            <p class="no-results-text">
                                No hay coches que coincidan con los criterios de búsqueda seleccionados.
                                Intenta ajustar los filtros para ver más opciones.
                            </p>
                            <a href="index.php" class="btn-primary-custom">
                                <i class="fas fa-home"></i> Volver al Inicio
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($resultados as $coche): ?>
                                <div class="col-lg-6">
                                    <div class="car-card">
                                        <div class="car-image-container">
                                            <img src="https://via.placeholder.com/400x300/FF6B35/FFFFFF?text=<?php echo urlencode($coche['marca'] . ' ' . $coche['modelo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?>" 
                                                 class="car-image">
                                            <?php if ($coche['destacado']): ?>
                                                <div class="car-badge">DESTACADO</div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="car-details">
                                            <h3 class="car-title">
                                                <?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?>
                                            </h3>
                                            <div class="car-specs">
                                                <div class="spec-item">
                                                    <i class="fas fa-calendar"></i>
                                                    <span><?php echo $coche['anio']; ?></span>
                                                </div>
                                                <div class="spec-item">
                                                    <i class="fas fa-tachometer-alt"></i>
                                                    <span><?php echo number_format($coche['kilometraje'], 0, ',', '.'); ?> km</span>
                                                </div>
                                                <div class="spec-item">
                                                    <i class="fas fa-gas-pump"></i>
                                                    <span><?php echo htmlspecialchars($coche['combustible']); ?></span>
                                                </div>
                                                <div class="spec-item">
                                                    <i class="fas fa-cog"></i>
                                                    <span><?php echo htmlspecialchars($coche['cambio'] ?? 'Manual'); ?></span>
                                                </div>
                                            </div>
                                            <div class="car-price">
                                                <?php echo number_format($coche['precio'], 0, ',', '.'); ?>€
                                            </div>
                                            <div class="car-actions">
                                                <button class="btn-primary-custom">
                                                    <i class="fas fa-eye"></i> Ver Detalles
                                                </button>
                                                <button class="btn-secondary-custom">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
