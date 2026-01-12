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
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--gradient-2);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link.active::after {
            width: 100%;
        }

        /* User Menu Styles */
        .user-menu-container {
            position: relative;
        }

        .user-icon-btn {
            color: var(--dark-color) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none !important;
            background: none !important;
            padding: 8px 12px !important;
            border-radius: 8px;
        }

        .user-icon-btn:hover {
            color: var(--primary-color) !important;
            background: rgba(255, 107, 53, 0.1) !important;
        }

        .user-name {
            font-weight: 600;
        }

        .user-popup {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            min-width: 250px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            margin-top: 10px;
        }

        .user-popup.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .popup-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .popup-header i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .popup-actions {
            padding: 10px 0;
        }

        .popup-action {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .popup-action:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .popup-action.logout:hover {
            background: #fee;
            color: #dc3545;
        }

        .popup-action i {
            width: 20px;
            text-align: center;
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
            object-fit: contain;
            object-position: center center;
            background: #ffffff;
            transition: transform 0.3s ease;
        }

        .car-card:hover .car-image {
            transform: none;
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
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 78, 137, 0.3);
            text-decoration: none;
            color: white;
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
    <?php $currentAction = $_GET['action'] ?? 'index'; ?>
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
                        <a class="nav-link <?php echo $currentAction === 'index' ? 'active' : ''; ?>" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentAction === 'buscar' ? 'active' : ''; ?>" href="index.php?action=buscar">Coches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#destacados">Destacados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#contacto">Contacto</a>
                    </li>
                    
                    <li class="nav-item">
                        <div class="user-menu-container">
                            <button class="btn btn-link nav-link user-icon-btn" id="userMenuBtn">
                                <i class="fas fa-user"></i>
                                <?php if ($estaLogueado): ?>
                                    <span class="user-name ms-2"><?php echo htmlspecialchars($usuarioActual['Nombre']); ?></span>
                                <?php endif; ?>
                            </button>
                            
                            <!-- Popup Menu -->
                            <div class="user-popup" id="userPopup">
                                <?php if ($estaLogueado): ?>
                                    <div class="popup-header">
                                        <i class="fas fa-user-circle"></i>
                                        <span><?php echo htmlspecialchars($usuarioActual['Nombre']); ?></span>
                                    </div>
                                    <div class="popup-actions">
                                        <a href="index.php?action=perfil" class="popup-action">
                                            <i class="fas fa-user"></i>
                                            <span>Ver Perfil</span>
                                        </a>
                                        <?php if ($esAdmin): ?>
                                            <a href="index.php?action=admin" class="popup-action">
                                                <i class="fas fa-cog"></i>
                                                <span>Administración</span>
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?action=logout" class="popup-action logout">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Cerrar Sesión</span>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="popup-header">
                                        <i class="fas fa-user-circle"></i>
                                        <span>Mi Cuenta</span>
                                    </div>
                                    <div class="popup-actions">
                                        <a href="index.php?action=login" class="popup-action">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>Iniciar Sesión</span>
                                        </a>
                                        <a href="index.php?action=registro" class="popup-action">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Registrarse</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
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
                        
                        <form id="filterForm" action="index.php" method="GET">
                            <input type="hidden" name="action" value="buscar">
                            
                            <div class="filter-group">
                                <label class="filter-label">Marca</label>
                                <select class="form-select" name="marca" id="marcaSelect">
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
                                <label class="filter-label">Modelo</label>
                                <select class="form-select" name="modelo" id="modeloSelect">
                                    <option value="">Todos los modelos</option>
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
                                <label class="filter-label">Combustible</label>
                                <select class="form-select" name="combustible">
                                    <option value="">Todos los combustibles</option>
                                    <option value="Gasolina" <?php echo (isset($_GET['combustible']) && $_GET['combustible'] == 'Gasolina') ? 'selected' : ''; ?>>Gasolina</option>
                                    <option value="Diesel" <?php echo (isset($_GET['combustible']) && $_GET['combustible'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                                    <option value="Eléctrico" <?php echo (isset($_GET['combustible']) && $_GET['combustible'] == 'Eléctrico') ? 'selected' : ''; ?>>Eléctrico</option>
                                    <option value="Híbrido" <?php echo (isset($_GET['combustible']) && $_GET['combustible'] == 'Híbrido') ? 'selected' : ''; ?>>Híbrido</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Año</label>
                                <div class="price-range">
                                    <input type="number" class="form-control" name="año_min" 
                                           placeholder="Desde" value="<?php echo htmlspecialchars($_GET['año_min'] ?? ''); ?>">
                                    <span>-</span>
                                    <input type="number" class="form-control" name="año_max" 
                                           placeholder="Hasta" value="<?php echo htmlspecialchars($_GET['año_max'] ?? ''); ?>">
                                </div>
                            </div>

                            <button type="submit" class="filter-btn">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-9" id="resultsContainer">
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
                                            <?php
                                                $imgPath = isset($coche['imagen']) && $coche['imagen']
                                                    ? $coche['imagen']
                                                    : 'https://via.placeholder.com/400x300/FF6B35/FFFFFF?text=' . urlencode($coche['marca'] . ' ' . $coche['modelo']);

                                                // Normalizar a ruta web
                                                if (strpos($imgPath, 'http') === 0) {
                                                    $img = $imgPath; // URL absoluta ya válida
                                                } else {
                                                    $p = str_replace('\\', '/', $imgPath);
                                                    // Intentar cortar desde /htdocs/ o /AutoZen/
                                                    if (($pos = stripos($p, '/htdocs/')) !== false) {
                                                        $rel = substr($p, $pos + strlen('/htdocs/')); // AutoZen/imagenes/...
                                                        $img = '/' . ltrim($rel, '/');
                                                    } elseif (($pos = stripos($p, '/AutoZen/')) !== false) {
                                                        $rel = substr($p, $pos); // /AutoZen/imagenes/...
                                                        $img = $rel;
                                                    } else {
                                                        // Tratar como relativa
                                                        $img = '/' . ltrim($p, '/');
                                                    }

                                                    // Prepend base del script si hiciera falta
                                                    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
                                                    if ($base && strpos($img, $base) !== 0) {
                                                        $img = $base . $img;
                                                    }
                                                }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($img); ?>"
                                                 alt="<?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?>"
                                                 class="car-image">
                                            <?php if (!empty($coche['destacado'])): ?>
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
                                                    <span><?php echo isset($coche['año']) ? (int)$coche['año'] : ''; ?></span>
                                                </div>
                                                <div class="spec-item">
                                                    <i class="fas fa-tachometer-alt"></i>
                                                    <span><?php echo isset($coche['km']) ? number_format($coche['km'], 0, ',', '.') : '0'; ?> km</span>
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
                                                <a class="btn-primary-custom" href="index.php?action=detalle&idVehiculo=<?php echo (int)$coche['idVehiculo']; ?>">
                                                    <i class="fas fa-eye"></i> Ver Detalles
                                                </a>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const marcaSelect = document.getElementById('marcaSelect');
    const modeloSelect = document.getElementById('modeloSelect');
    const filterForm = document.getElementById('filterForm');
    const resultsContainer = document.getElementById('resultsContainer');

    const userMenuBtn = document.getElementById('userMenuBtn');
    const userPopup = document.getElementById('userPopup');

    // Load models when brand changes
    marcaSelect.addEventListener('change', function() {
        const marcaId = this.value;
        
        // Clear current models
        modeloSelect.innerHTML = '<option value="">Todos los modelos</option>';
        
        if (marcaId) {
            // Fetch models for selected brand
            fetch(`index.php?action=getModelos&marca=${marcaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        // Show message when no models are available
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "No hay ningun modelo disponible en este momento";
                        option.disabled = true;
                        modeloSelect.appendChild(option);
                    } else {
                        data.forEach(modelo => {
                            const option = document.createElement('option');
                            option.value = modelo.id;
                            option.textContent = modelo.nombre;
                            modeloSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading models:', error);
                });
        }
    });
    
    // Handle form submission with AJAX
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        resultsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        
        // Get form data
        const formData = new FormData(filterForm);
        const queryString = new URLSearchParams(formData).toString();
        
        // Make AJAX request
        fetch(`index.php?action=buscar&${queryString}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Directly use the returned HTML since AJAX returns only the results
            resultsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = '<div class="no-results"><h3>Error de conexión</h3><p>No se pudieron cargar los resultados. Verifica tu conexión a internet.</p></div>';
        });
    });
    
    // Trigger change event if brand is pre-selected
    <?php if (isset($_GET['marca'])): ?>
        if (marcaSelect.value) {
            marcaSelect.dispatchEvent(new Event('change'));
        }
    <?php endif; ?>

    if (userMenuBtn && userPopup) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userPopup.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!userMenuBtn.contains(e.target) && !userPopup.contains(e.target)) {
                userPopup.classList.remove('show');
            }
        });
    }
});
</script>
</body>
</html>
