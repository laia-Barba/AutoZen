<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoZen - Coches de Segunda Mano</title>
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
            overflow-x: hidden;
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
            transform: translateY(-2px);
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

        /* Hero Section */
        .hero-section {
            margin-top: 80px;
            padding: 100px 0;
            background: var(--gradient-1);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,133.3C960,128,1056,96,1152,90.7C1248,85,1344,107,1392,117.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .search-form {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease 0.4s both;
        }

        .search-btn {
            background: var(--gradient-2);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            width: 100%;
            min-height: 48px;
        }

        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: white;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            background: var(--light-bg);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-color);
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.2);
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: var(--gradient-2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-weight: 500;
        }

        /* Cars Section */
        .cars-section {
            padding: 80px 0;
            background: var(--light-bg);
        }

        .section-title {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 3rem;
            font-size: 1.2rem;
        }

        .car-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .car-image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
            position: relative;
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
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .car-details {
            padding: 1.5rem;
        }

        .car-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .car-specs {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            flex-wrap: wrap;
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .car-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 1rem 0;
        }

        .car-btn {
            width: 100%;
            background: var(--gradient-3);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .car-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 78, 137, 0.3);
        }

        /* Footer */
        footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 80px;
        }

        .footer-brand {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .search-form {
                padding: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=buscar">Coches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destacados">Destacados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
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
                                            <a href="#" class="popup-action">
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

    <!-- Success Messages -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-section" id="inicio">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Encuentra tu coche perfecto</h1>
                    <p class="hero-subtitle">Los mejores coches de segunda mano están aquí</p>
                    
                    <!-- Formulario de búsqueda -->
                    <form class="search-form" action="index.php" method="GET">
                        <input type="hidden" name="action" value="buscar">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select class="form-select" name="marca">
                                    <option value="">Todas las marcas</option>
                                    <?php foreach ($marcas as $marca): ?>
                                        <option value="<?php echo $marca['id']; ?>"><?php echo htmlspecialchars($marca['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="precio_min" placeholder="Precio mín">
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="precio_max" placeholder="Precio máx">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="search-btn w-100">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="hero-content text-center">
                        <i class="fas fa-car-side" style="font-size: 15rem; color: rgba(255, 255, 255, 0.2);"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($estadisticas['total_coches']); ?></div>
                        <div class="stat-label">Coches Disponibles</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($estadisticas['total_marcas']); ?></div>
                        <div class="stat-label">Marcas</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($estadisticas['precio_promedio'], 0, ',', '.'); ?>€</div>
                        <div class="stat-label">Precio Promedio</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number">4.8</div>
                        <div class="stat-label">Valoración Media</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coches Destacados -->
    <section class="cars-section" id="destacados">
        <div class="container">
            <h2 class="section-title">Coches Destacados</h2>
            <p class="section-subtitle">Las mejores ofertas seleccionadas para ti</p>
            
            <div class="row">
                <?php if (empty($cochesDestacados)): ?>
                    <div class="col-12 text-center">
                        <p>No hay coches destacados disponibles en este momento.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cochesDestacados as $coche): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="car-card">
                                <div class="car-image-container">
                                    <img src="https://via.placeholder.com/400x300/FF6B35/FFFFFF?text=<?php echo urlencode($coche['marca'] . ' ' . $coche['modelo']); ?>" 
                                         alt="<?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?>" 
                                         class="car-image">
                                </div>
                                <div class="car-badge">DESTACADO</div>
                                <div class="car-details">
                                    <h3 class="car-title"><?php echo htmlspecialchars($coche['marca'] . ' ' . $coche['modelo']); ?></h3>
                                    <div class="car-specs">
                                        <div class="spec-item">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo $coche['anio']; ?>
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <?php echo number_format($coche['kilometraje'], 0, ',', '.'); ?> km
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-gas-pump"></i>
                                            <?php echo htmlspecialchars($coche['combustible']); ?>
                                        </div>
                                    </div>
                                    <div class="car-price"><?php echo number_format($coche['precio'], 0, ',', '.'); ?>€</div>
                                    <button class="car-btn">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    
    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h3 class="footer-brand">
                        <i class="fas fa-car"></i> AutoZen
                    </h3>
                    <p>Tu destino confiable para encontrar los mejores coches de segunda mano con calidad garantizada.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#inicio" class="text-white-50">Inicio</a></li>
                        <li class="mb-2"><a href="#coches" class="text-white-50">Coches</a></li>
                        <li class="mb-2"><a href="#destacados" class="text-white-50">Destacados</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Sobre Nosotros</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-phone"></i> +34 900 123 456</li>
                        <li class="mb-2"><i class="fas fa-envelope"></i> info@autozen.com</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt"></i> Calle Principal 123, Madrid</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 AutoZen. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // User Menu Popup Functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 AutoZen - User menu initialized');
            
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userPopup = document.getElementById('userPopup');
            
            if (userMenuBtn && userPopup) {
                console.log('✅ User menu elements found');
                
                // Toggle popup
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    console.log('👤 User menu button clicked');
                    
                    const isOpen = userPopup.classList.contains('show');
                    
                    if (isOpen) {
                        console.log('🔽 Closing user popup');
                        userPopup.classList.remove('show');
                    } else {
                        console.log('🔼 Opening user popup');
                        userPopup.classList.add('show');
                        
                        // Log user status
                        <?php if ($estaLogueado): ?>
                            console.log('👋 User logged in:', '<?php echo htmlspecialchars($usuarioActual['Nombre']); ?>');
                            console.log('🔑 Is admin:', <?php echo $esAdmin ? 'true' : 'false'; ?>);
                        <?php else: ?>
                            console.log('🔒 User not logged in');
                        <?php endif; ?>
                    }
                });
                
                // Close popup when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target) && !userPopup.contains(e.target)) {
                        if (userPopup.classList.contains('show')) {
                            console.log('🔽 Closing popup (clicked outside)');
                            userPopup.classList.remove('show');
                        }
                    }
                });
                
                // Log popup action clicks
                const popupActions = userPopup.querySelectorAll('.popup-action');
                popupActions.forEach(action => {
                    action.addEventListener('click', function() {
                        const actionText = this.querySelector('span').textContent;
                        console.log('🎯 Popup action clicked:', actionText);
                    });
                });
                
            } else {
                console.error('❌ User menu elements not found');
            }
        });
    </script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.car-card, .stat-card').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
