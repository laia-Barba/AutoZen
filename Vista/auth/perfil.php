<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - AutoZen</title>
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

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .main-content {
            margin-top: 100px;
            padding: 40px 0;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--light-bg);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 600;
            margin-right: 30px;
        }

        .profile-info h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .profile-info p {
            color: #6c757d;
            font-size: 1.1rem;
            margin: 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 10px;
        }

        .info-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
            width: 30px;
            margin-right: 15px;
        }

        .info-item .label {
            font-weight: 500;
            color: var(--dark-color);
            margin-right: 10px;
        }

        .info-item .value {
            color: #6c757d;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
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
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
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
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['errores'])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($_SESSION['errores'] as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>

            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($usuario['Nombre'], 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($usuario['Nombre']); ?></h2>
                        <p><?php echo htmlspecialchars($usuario['Correo']); ?></p>
                        <?php if ($usuario['isAdmin']): ?>
                            <span class="badge bg-danger">Administrador</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-4">Información Personal</h4>
                        
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <span class="label">Nombre:</span>
                            <span class="value"><?php echo htmlspecialchars($usuario['Nombre']); ?></span>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span class="label">Correo:</span>
                            <span class="value"><?php echo htmlspecialchars($usuario['Correo']); ?></span>
                        </div>

                        <?php if (!empty($usuario['Telefono'])): ?>
                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <span class="label">Teléfono:</span>
                                <span class="value"><?php echo htmlspecialchars($usuario['Telefono']); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span class="label">Tipo de cuenta:</span>
                            <span class="value"><?php echo $usuario['isAdmin'] ? 'Administrador' : 'Usuario'; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4 class="mb-4">Acciones</h4>
                        
                        <div class="d-grid gap-3">
                            <a href="index.php?action=editProfile" class="btn btn-primary-custom">
                                <i class="fas fa-edit me-2"></i>
                                Editar Perfil
                            </a>
                            
                            <a href="index.php?action=changePassword" class="btn btn-secondary-custom">
                                <i class="fas fa-key me-2"></i>
                                Cambiar Contraseña
                            </a>
                            
                            <?php if ($usuario['isAdmin']): ?>
                                <a href="index.php?action=admin" class="btn btn-secondary-custom">
                                    <i class="fas fa-cog me-2"></i>
                                    Panel de Administración
                                </a>
                            <?php endif; ?>
                            
                            <a href="index.php?action=buscar" class="btn btn-secondary-custom">
                                <i class="fas fa-search me-2"></i>
                                Buscar Coches
                            </a>
                            
                            <a href="index.php?action=logout" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
