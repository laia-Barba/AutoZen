<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - AutoZen</title>
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
            background-color: var(--light-bg);
            min-height: 100vh;
        }

        .main-content {
            margin-top: 80px;
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
    <a href="index.php?action=perfil" class="btn btn-secondary-custom">
        <i class="fas fa-arrow-left me-2"></i>
        Volver al Perfil
    </a>
</div>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card-custom">
                    <h3 class="mb-2"><i class="fas fa-user-edit me-2"></i>Editar Perfil</h3>
                    <p class="text-muted mb-4">Actualiza tu nombre, correo y teléfono.</p>

                    <?php if (isset($_SESSION['errores'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($_SESSION['errores'] as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errores']); ?>
                    <?php endif; ?>

                    <?php
                        $nombreValue = $_SESSION['datos_formulario']['nombre'] ?? ($usuario['Nombre'] ?? '');
                        $correoValue = $_SESSION['datos_formulario']['correo'] ?? ($usuario['Correo'] ?? '');
                        $telefonoValue = $_SESSION['datos_formulario']['telefono'] ?? ($usuario['Telefono'] ?? '');
                        unset($_SESSION['datos_formulario']);
                    ?>

                    <form action="index.php?action=saveProfile" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required value="<?php echo htmlspecialchars((string)$nombreValue); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" required value="<?php echo htmlspecialchars((string)$correoValue); ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars((string)$telefonoValue); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100">
                            Guardar cambios
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
