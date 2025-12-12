<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #F7931E;
            --dark-color: #2C3E50;
            --light-bg: #F8F9FA;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-item {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 1rem;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 30px;
        }

        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }

        .sidebar-item i {
            width: 25px;
            margin-right: 10px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 30px;
        }

        .content-header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .content-header h1 {
            color: var(--dark-color);
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .content-header p {
            color: #6c757d;
            margin: 10px 0 0 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 1rem;
        }

        .page-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .page-content.active {
            display: block;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-car fa-2x mb-3"></i>
                <h3>AutoZen Admin</h3>
                <small>Panel de Control</small>
            </div>
            <nav class="sidebar-menu">
                <a href="#" class="sidebar-item active" data-page="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="#" class="sidebar-item" data-page="addCar">
                    <i class="fas fa-plus-circle"></i>
                    Añadir Coche
                </a>
                <a href="#" class="sidebar-item" data-page="manageCars">
                    <i class="fas fa-car"></i>
                    Gestionar Coches
                </a>
                <a href="#" class="sidebar-item" data-page="users">
                    <i class="fas fa-users"></i>
                    Usuarios
                </a>
                <a href="#" class="sidebar-item" data-page="settings">
                    <i class="fas fa-cog"></i>
                    Configuración
                </a>
                <a href="index.php" class="sidebar-item">
                    <i class="fas fa-home"></i>
                    Volver al Sitio
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>Panel de Administración</h1>
                <p>Bienvenido, <?php echo htmlspecialchars($usuarioActual['Nombre']); ?>. Gestiona tu concesionario desde aquí.</p>
            </div>

            <!-- Dashboard Page -->
            <div class="page-content active" id="dashboard-page">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="stat-number">24</div>
                        <div class="stat-label">Total Coches</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">156</div>
                        <div class="stat-label">Usuarios</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-number">1,234</div>
                        <div class="stat-label">Visitas Hoy</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number">4.8</div>
                        <div class="stat-label">Valoración</div>
                    </div>
                </div>

                <h3 class="mb-4">Actividad Reciente</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12/12/2025</td>
                                <td>Laia Barba</td>
                                <td>Registro</td>
                                <td>Nuevo usuario registrado</td>
                            </tr>
                            <tr>
                                <td>12/12/2025</td>
                                <td>Admin</td>
                                <td>Añadir Coche</td>
                                <td>BMW Serie 3 añadido</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Car Page -->
            <div class="page-content" id="addCar-page">
                <h2 class="mb-4">Añadir Nuevo Coche</h2>
                <p>Completa el formulario para añadir un nuevo coche al inventario.</p>
                
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" class="form-control" placeholder="Ej: BMW">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" class="form-control" placeholder="Ej: Serie 3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Año</label>
                            <input type="number" class="form-control" placeholder="Ej: 2022">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" class="form-control" placeholder="Ej: 25000">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kilometraje</label>
                            <input type="number" class="form-control" placeholder="Ej: 15000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Combustible</label>
                            <select class="form-control">
                                <option>Gasolina</option>
                                <option>Diesel</option>
                                <option>Híbrido</option>
                                <option>Eléctrico</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" rows="4" placeholder="Describe las características del coche..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn-primary-custom">
                        <i class="fas fa-save me-2"></i>Guardar Coche
                    </button>
                </form>
            </div>

            <!-- Other pages (placeholder content) -->
            <div class="page-content" id="manageCars-page">
                <h2 class="mb-4">Gestionar Coches</h2>
                <p>Función en desarrollo...</p>
            </div>

            <div class="page-content" id="users-page">
                <h2 class="mb-4">Gestionar Usuarios</h2>
                <p>Función en desarrollo...</p>
            </div>

            <div class="page-content" id="settings-page">
                <h2 class="mb-4">Configuración</h2>
                <p>Función en desarrollo...</p>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar navigation
            const sidebarItems = document.querySelectorAll('.sidebar-item[data-page]');
            const pageContents = document.querySelectorAll('.page-content');

            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all items and pages
                    sidebarItems.forEach(i => i.classList.remove('active'));
                    pageContents.forEach(p => p.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Show corresponding page
                    const pageId = this.getAttribute('data-page') + '-page';
                    const targetPage = document.getElementById(pageId);
                    if (targetPage) {
                        targetPage.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
