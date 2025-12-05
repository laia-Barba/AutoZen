<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AutoZen</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 600px;
        }

        .auth-left {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            flex: 1;
        }

        .auth-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
        }

        .auth-brand {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .auth-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-auth {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }

        .auth-links {
            text-align: center;
            margin-top: 30px;
        }

        .auth-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: var(--secondary-color);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-check {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                max-width: 400px;
            }

            .auth-left {
                padding: 40px 30px;
            }

            .auth-right {
                padding: 40px 30px;
            }

            .auth-brand {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-brand">
                <i class="fas fa-car"></i>
                AutoZen
            </div>
            <h2 class="auth-title">¡Bienvenido de nuevo!</h2>
            <p class="auth-subtitle">Inicia sesión para acceder a tu cuenta y descubrir los mejores coches.</p>
            <div class="features">
                <div class="feature-item mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Busca y filtra coches fácilmente</span>
                </div>
                <div class="feature-item mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Guarda tus favoritos</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Recibe notificaciones de nuevas ofertas</span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <h2 class="auth-title">Iniciar Sesión</h2>
            
            <?php if (isset($_SESSION['errores'])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($_SESSION['errores'] as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>

            <form action="index.php?action=login" method="POST" id="loginForm">
                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="correo" 
                               value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['correo'] ?? $_COOKIE['recordar_correo'] ?? ''); ?>" 
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="contraseña" required>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="recordar" id="recordar">
                    <label class="form-check-label" for="recordar">
                        Recordar mi correo
                    </label>
                </div>

                <button type="submit" class="btn btn-auth">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="auth-links">
                <p>¿No tienes cuenta? <a href="index.php?action=registro">Regístrate aquí</a></p>
                <p><a href="index.php">Volver al inicio</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    console.log('🔐 Login form submitting...');
                    console.log('📧 Email:', this.correo.value);
                    console.log('🔑 Password length:', this.contraseña.value.length);
                    
                    // Log form data
                    const formData = new FormData(this);
                    console.log('📋 Form data:');
                    for (let [key, value] of formData.entries()) {
                        if (key === 'contraseña') {
                            console.log(key + ': [HIDDEN - ' + value.length + ' chars]');
                        } else {
                            console.log(key + ':', value);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
