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
            --success-color: #28a745;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
        }

        .page-wrap {
            padding: 100px 0 40px;
        }

        .card-soft {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
            overflow: hidden;
            border: none;
            margin-bottom: 2rem;
        }

        .card-header-soft {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: #fff;
            padding: 24px;
            border: none;
        }

        .card-header-soft h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .btn-primary-custom:hover {
            background: #e05a1a;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .btn-outline-secondary-custom {
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            border: 2px solid #dee2e6;
            color: #495057;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary-custom:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .vehicle-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            background: #fff;
        }

        .vehicle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .vehicle-image {
            height: 180px;
            object-fit: cover;
            width: 100%;
            border-bottom: 1px solid #e9ecef;
        }

        .vehicle-details {
            padding: 20px;
        }

        .vehicle-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--dark-color);
        }

        .vehicle-specs {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .vehicle-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .reservation-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid var(--success-color);
            display: none;
        }

        .reservation-info h5 {
            color: var(--success-color);
            font-size: 1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reservation-detail {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .reservation-detail i {
            margin-right: 10px;
            color: var(--secondary-color);
            margin-top: 3px;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-cart i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-cart h4 {
            color: #6c757d;
            margin-bottom: 15px;
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
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart"></i>
                                <h4>Tu carrito está vacío</h4>
                                <p>Añade vehículos a tu carrito para verlos aquí</p>
                                <a href="index.php?action=buscar" class="btn btn-primary-custom mt-3">
                                    <i class="fas fa-car me-2"></i>Ver vehículos disponibles
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <div class="row">
                                    <?php foreach ($vehiculosCarrito as $v): 
                                        $imagen = !empty($v['imagen']) ? $v['imagen'] : 'assets/img/vehicle-placeholder.jpg';
                                        $reservado = isset($v['reservado']) && $v['reservado'] === true;
                                    ?>
                                        <div class="col-md-6 mb-4">
                                            <div class="vehicle-card">
                                                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars(($v['marca'] ?? '') . ' ' . ($v['modelo'] ?? '')); ?>" class="vehicle-image">
                                                <div class="vehicle-details">
                                                    <h3 class="vehicle-title">
                                                        <?php echo htmlspecialchars(($v['marca'] ?? '') . ' ' . ($v['modelo'] ?? '')); ?>
                                                        <?php if ($reservado): ?>
                                                            <span class="badge bg-success ms-2">Reservado</span>
                                                        <?php endif; ?>
                                                    </h3>
                                                    <div class="vehicle-specs">
                                                        <div><?php echo isset($v['año']) ? (int)$v['año'] : ''; ?> · <?php echo isset($v['km']) ? number_format((float)$v['km'], 0, ',', '.') : '0'; ?> km;</div>
                                                        <div><?php echo htmlspecialchars((string)($v['combustible'] ?? '')); ?> · <?php echo htmlspecialchars((string)($v['transmision'] ?? '')); ?></div>
                                                    </div>
                                                    <div class="vehicle-price">
                                                        <?php echo isset($v['precio']) ? number_format((float)$v['precio'], 0, ',', '.') : '0'; ?>€
                                                    </div>
                                                    
                                                    <?php if (!$reservado): ?>
                                                        <button class="btn btn-primary-custom w-100 reserve-btn" data-vehicle-id="<?php echo (int)($v['idVehiculo'] ?? 0); ?>">
                                                            <i class="fas fa-calendar-check me-2"></i>Reservar Ahora
                                                        </button>
                                                    <?php endif; ?>

                                                    <div class="reservation-info" id="reservation-info-<?php echo (int)($v['idVehiculo'] ?? 0); ?>">
                                                        <h5><i class="fas fa-check-circle"></i> ¡Reservado con éxito!</h5>
                                                        <div class="reservation-detail">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <div>
                                                                <strong>Dirección:</strong><br>
                                                                Calle del Concesionario, 123<br>
                                                                28001 Madrid, España
                                                            </div>
                                                        </div>
                                                        <div class="reservation-detail">
                                                            <i class="far fa-clock"></i>
                                                            <div>
                                                                <strong>Horario de atención:</strong><br>
                                                                Lunes a Viernes: 9:00 - 20:00<br>
                                                                Sábados: 10:00 - 14:00
                                                            </div>
                                                        </div>
                                                        <div class="reservation-detail">
                                                            <i class="fas fa-phone-alt"></i>
                                                            <div>
                                                                <strong>Teléfono:</strong> 912 345 678
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle reservation button clicks
        document.querySelectorAll('.reserve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const vehicleId = this.getAttribute('data-vehicle-id');
                const vehicleCard = this.closest('.vehicle-card');
                const reserveButton = this;
                const reservationInfo = document.getElementById(`reservation-info-${vehicleId}`);
                
                // Show loading state
                const originalText = reserveButton.innerHTML;
                reserveButton.disabled = true;
                reserveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Reservando...';
                
                // Simulate API call (replace with actual API call)
                setTimeout(() => {
                    // On success
                    reserveButton.classList.add('d-none');
                    reservationInfo.style.display = 'block';
                    
                    // Add reserved badge
                    const title = vehicleCard.querySelector('.vehicle-title');
                    if (title && !title.querySelector('.badge')) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-success ms-2';
                        badge.textContent = 'Reservado';
                        title.appendChild(badge);
                    }
                    
                    // Show success toast
                    showToast('¡Vehículo reservado con éxito!', false, 'success');
                }, 1000);
            });
        });
    });
    
    // Toast notification function
    function showToast(message, isError = false, type = 'info') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) return;
        
        const toast = document.createElement('div');
        toast.className = `toast show align-items-center text-white bg-${isError ? 'danger' : type} border-0`;
        toast.role = 'alert';
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove toast after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
</script>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast-container" class="toast-container">
        <!-- Toasts will be added here dynamically -->
    </div>
</div>
</body>
</html>
