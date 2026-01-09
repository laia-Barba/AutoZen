<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars((string)($pageTitle ?? '')); ?></title>
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

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
        }

        .main-content {
            margin-top: 60px;
            padding: 40px 0;
        }

        .card-custom {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 35px;
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
    </style>
</head>
<body>
<div class="container mt-4">
    <a href="<?php echo htmlspecialchars((string)($backHref ?? 'index.php')); ?>" class="btn btn-secondary-custom">
        <i class="fas fa-arrow-left me-2"></i>
        <?php echo htmlspecialchars((string)($backText ?? 'Volver')); ?>
    </a>
</div>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card-custom">
                    <h3 class="mb-2"><i class="<?php echo htmlspecialchars((string)($headerIcon ?? 'fas fa-lock')); ?> me-2"></i><?php echo htmlspecialchars((string)($headerTitle ?? '')); ?></h3>
                    <p class="text-muted mb-4">Paso 2 de 2: escribe tu nueva contraseña.</p>

                    <?php if (isset($_SESSION['errores'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($_SESSION['errores'] as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errores']); ?>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars((string)($formAction ?? '')); ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="nueva_contraseña" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" name="confirmar_nueva_contraseña" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100">
                            Guardar
                            <i class="fas fa-check ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
