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
            padding: 80px 20px 40px;
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

<div class="container-fluid page-wrap">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
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
                                        $imagen = !empty($v['imagen']) ? 'uploads/vehiculos/' . basename($v['imagen']) : 'assets/img/vehicle-placeholder.jpg';
                                        $reservado = isset($v['reservado']) && ($v['reservado'] === true || $v['reservado'] == 1);
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
                                                        <button class="btn btn-outline-danger w-100 mt-2 remove-from-cart-btn" data-vehicle-id="<?php echo (int)($v['idVehiculo'] ?? 0); ?>">
                                                            <i class="fas fa-trash me-2"></i>Quitar del Carrito
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-outline-warning w-100 cancel-reservation-btn" data-vehicle-id="<?php echo (int)($v['idVehiculo'] ?? 0); ?>">
                                                            <i class="fas fa-times-circle me-2"></i>Cancelar Reserva
                                                        </button>
                                                    <?php endif; ?>

                                                    <div class="reservation-info" id="reservation-info-<?php echo (int)($v['idVehiculo'] ?? 0); ?>" style="<?php echo $reservado ? 'display: block;' : 'display: none;'; ?>">
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
                
                // Make API call to reserve vehicle
                fetch('index.php?action=reservarVehiculo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `idVehiculo=${vehicleId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
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
                        
                        // Show cancel button and hide remove button
                        const removeBtn = vehicleCard.querySelector('.remove-from-cart-btn');
                        const cancelBtn = document.createElement('button');
                        cancelBtn.className = 'btn btn-outline-warning w-100 cancel-reservation-btn';
                        cancelBtn.setAttribute('data-vehicle-id', vehicleId);
                        cancelBtn.innerHTML = '<i class="fas fa-times-circle me-2"></i>Cancelar Reserva';
                        
                        if (removeBtn) {
                            removeBtn.parentNode.replaceChild(cancelBtn, removeBtn);
                        } else {
                            reserveButton.parentNode.appendChild(cancelBtn);
                        }
                        
                        // Re-attach event listener to new cancel button
                        attachCancelReservationListener(cancelBtn);
                        
                        // Show success toast
                        showToast(data.message || '¡Vehículo reservado con éxito!', false, 'success');
                    } else {
                        // On error
                        reserveButton.disabled = false;
                        reserveButton.innerHTML = originalText;
                        showToast(data.errors?.[0] || 'Error al reservar el vehículo', true, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    reserveButton.disabled = false;
                    reserveButton.innerHTML = originalText;
                    showToast('Error de conexión al reservar el vehículo', true, 'danger');
                });
            });
        });

        // Handle cancel reservation button clicks
        function attachCancelReservationListener(button) {
            button.addEventListener('click', function() {
                const vehicleId = this.getAttribute('data-vehicle-id');
                const vehicleCard = this.closest('.vehicle-card');
                const cancelButton = this;
                const reservationInfo = document.getElementById(`reservation-info-${vehicleId}`);
                
                // Show loading state
                const originalText = cancelButton.innerHTML;
                cancelButton.disabled = true;
                cancelButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cancelando...';
                
                // Make API call to cancel reservation
                fetch('index.php?action=cancelarReserva', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `idVehiculo=${vehicleId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        // On success
                        reservationInfo.style.display = 'none';
                        
                        // Remove reserved badge
                        const title = vehicleCard.querySelector('.vehicle-title');
                        const badge = title.querySelector('.badge');
                        if (badge) {
                            badge.remove();
                        }
                        
                        // Show reserve button and remove button again
                        const reserveBtn = document.createElement('button');
                        reserveBtn.className = 'btn btn-primary-custom w-100 reserve-btn';
                        reserveBtn.setAttribute('data-vehicle-id', vehicleId);
                        reserveBtn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Reservar Ahora';
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'btn btn-outline-danger w-100 mt-2 remove-from-cart-btn';
                        removeBtn.setAttribute('data-vehicle-id', vehicleId);
                        removeBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Quitar del Carrito';
                        
                        cancelButton.parentNode.replaceChild(reserveBtn, cancelButton);
                        reserveBtn.parentNode.appendChild(removeBtn);
                        
                        // Re-attach event listeners
                        attachReserveListener(reserveBtn);
                        attachRemoveFromCartListener(removeBtn);
                        
                        // Show success toast
                        showToast(data.message || '¡Reserva cancelada correctamente!', false, 'success');
                    } else {
                        // On error
                        cancelButton.disabled = false;
                        cancelButton.innerHTML = originalText;
                        showToast(data.errors?.[0] || 'Error al cancelar la reserva', true, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    cancelButton.disabled = false;
                    cancelButton.innerHTML = originalText;
                    showToast('Error de conexión al cancelar la reserva', true, 'danger');
                });
            });
        }

        // Handle remove from cart button clicks
        function attachRemoveFromCartListener(button) {
            button.addEventListener('click', function() {
                const vehicleId = this.getAttribute('data-vehicle-id');
                const vehicleCard = this.closest('.col-md-6');
                
                if (!confirm('¿Estás seguro de que quieres eliminar este vehículo del carrito?')) {
                    return;
                }
                
                // Show loading state
                const originalText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Eliminando...';
                
                // Make API call to remove from cart
                fetch('index.php?action=eliminarDelCarrito', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `idVehiculo=${vehicleId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        // Remove vehicle card with animation
                        vehicleCard.style.transition = 'opacity 0.3s, transform 0.3s';
                        vehicleCard.style.opacity = '0';
                        vehicleCard.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            vehicleCard.remove();
                            
                            // Check if cart is empty
                            const remainingVehicles = document.querySelectorAll('.vehicle-card');
                            if (remainingVehicles.length === 0) {
                                location.reload(); // Reload to show empty cart message
                            }
                        }, 300);
                        
                        // Update cart count
                        if (data.count !== undefined) {
                            updateCartBadge(data.count);
                        }
                        
                        // Show success toast
                        showToast(data.message || '¡Vehículo eliminado del carrito!', false, 'success');
                    } else {
                        // On error
                        this.disabled = false;
                        this.innerHTML = originalText;
                        showToast(data.errors?.[0] || 'Error al eliminar el vehículo del carrito', true, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.disabled = false;
                    this.innerHTML = originalText;
                    showToast('Error de conexión al eliminar el vehículo', true, 'danger');
                });
            });
        }

        // Helper functions to attach event listeners
        function attachReserveListener(button) {
            button.addEventListener('click', function() {
                button.click(); // This will trigger the original reserve button handler
            });
        }

        // Attach listeners to existing buttons
        document.querySelectorAll('.cancel-reservation-btn').forEach(attachCancelReservationListener);
        document.querySelectorAll('.remove-from-cart-btn').forEach(attachRemoveFromCartListener);
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
