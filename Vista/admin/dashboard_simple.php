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

        .admin-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .admin-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
            text-decoration: none;
            color: var(--dark-color);
        }

        .admin-card:hover {
            transform: translateY(-5px);
            text-decoration: none;
            color: var(--dark-color);
        }

        .admin-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .admin-description {
            color: #6c757d;
            margin-bottom: 0;
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
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
            padding: 12px 25px;
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
    </style>
</head>
<body>
    <!-- Back Button -->
    <div class="container mt-4">
        <a href="index.php" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Inicio
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="content-header">
                <h1>Panel de Administración</h1>
                <p>Bienvenido, <?php echo htmlspecialchars($usuarioActual['Nombre']); ?>. Gestiona tu concesionario desde aquí.</p>
            </div>

            <!-- Admin Menu -->
            <div class="admin-menu">
                <a href="index.php?action=addCar" class="admin-card">
                    <div class="admin-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="admin-title">Añadir Coche</div>
                    <div class="admin-description">Añade nuevos coches al inventario</div>
                </a>

                <a href="index.php?action=manageCars" class="admin-card">
                    <div class="admin-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="admin-title">Gestionar Coches</div>
                    <div class="admin-description">Edita y elimina coches existentes</div>
                </a>

                <a href="index.php?action=manageUsers" class="admin-card">
                    <div class="admin-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="admin-title">Usuarios</div>
                    <div class="admin-description">Gestiona los usuarios registrados</div>
                </a>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-number"><?php echo isset($totalCoches) ? (int)$totalCoches : 0; ?></div>
                    <div class="stat-label">Total Coches</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?php echo isset($totalUsuarios) ? (int)$totalUsuarios : 0; ?></div>
                    <div class="stat-label">Usuarios</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-number">4.8</div>
                    <div class="stat-label">Valoración</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
