<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #004E89;
            --dark-color: #1A1A2E;
            --light-bg: #F8F9FA;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
        }

        .page-wrap {
            padding: 120px 0 60px;
        }

        .card-soft {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
            overflow: hidden;
        }

        .card-header-soft {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: #fff;
            padding: 24px;
        }

        .card-header-soft h1 {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 12px 18px;
            border-radius: 12px;
        }

        .btn-primary-custom:hover {
            background: #f35a22;
            color: #fff;
        }

        .btn-outline-secondary-custom {
            border-radius: 12px;
            font-weight: 700;
            padding: 12px 18px;
        }
    </style>
</head>
<body>
<?php
    $correoPrefill = $_SESSION['datos_formulario']['correo']
        ?? ($_SESSION['usuario']['Correo'] ?? ($usuario['Correo'] ?? ''));
    $telefonoPrefill = $_SESSION['datos_formulario']['telefono']
        ?? ($_SESSION['usuario']['Telefono'] ?? ($usuario['Telefono'] ?? ''));
?>

<div class="container page-wrap">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <?php if (!empty($_SESSION['errores'])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($_SESSION['errores'] as $e): ?>
                        <div><?php echo htmlspecialchars((string)$e); ?></div>
                    <?php endforeach; unset($_SESSION['errores']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['mensaje'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars((string)$_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
                </div>
            <?php endif; ?>

            <div class="card-soft">
                <div class="card-header-soft">
                    <h1><i class="fas fa-shopping-cart me-2"></i>Carrito / Reserva</h1>
                    <div class="opacity-75 mt-1">
                        <?php echo !empty($needsContact) ? 'Confirma tus datos de contacto para poder reservar.' : 'Tus vehículos reservados.'; ?>
                    </div>
                </div>

                <div class="p-4 p-lg-4">
                    <?php if (!empty($needsContact)): ?>
                        <form action="index.php?action=carritoSave" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Correo electrónico</label>
                                <input type="email" class="form-control" name="correo" required value="<?php echo htmlspecialchars((string)$correoPrefill); ?>">
                                <div class="form-text">Lo usaremos para enviarte confirmaciones de la reserva.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" required inputmode="numeric" pattern="\d{9}" minlength="9" maxlength="9" title="Introduce un teléfono español de 9 dígitos" placeholder="Ej: 612345678" value="<?php echo htmlspecialchars((string)$telefonoPrefill); ?>">
                                <div class="form-text">Es obligatorio para poder reservar.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-save me-2"></i>Guardar
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary btn-outline-secondary-custom">
                                    <i class="fas fa-arrow-left me-2"></i>Volver
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <?php if (empty($vehiculosCarrito)): ?>
                            <div class="alert alert-info mb-0">Aún no tienes vehículos reservados en el carrito.</div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($vehiculosCarrito as $v): ?>
                                    <a href="index.php?action=detalle&idVehiculo=<?php echo (int)($v['idVehiculo'] ?? 0); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars((string)($v['marca'] ?? '')); ?> <?php echo htmlspecialchars((string)($v['modelo'] ?? '')); ?></div>
                                            <div class="text-muted small">
                                                <?php echo isset($v['año']) ? (int)$v['año'] : ''; ?> · <?php echo isset($v['km']) ? number_format((float)$v['km'], 0, ',', '.') : '0'; ?> km · <?php echo htmlspecialchars((string)($v['combustible'] ?? '')); ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill"><?php echo isset($v['precio']) ? number_format((float)$v['precio'], 0, ',', '.') : '0'; ?>€</span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-3">
                            <a href="index.php?action=buscar" class="btn btn-primary-custom">
                                <i class="fas fa-car me-2"></i>Seguir navegando
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary btn-outline-secondary-custom">
                                <i class="fas fa-home me-2"></i>Inicio
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
