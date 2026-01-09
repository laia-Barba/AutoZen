<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AutoZen</title>
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
            padding: 20px 0;
        }

        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 700px;
        }

        .auth-left {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
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
            overflow-y: auto;
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
            margin-bottom: 20px;
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

        .password-strength {
            margin-top: 5px;
            font-size: 0.85rem;
        }

        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }

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
            <h2 class="auth-title">Únete a AutoZen</h2>
            <p class="auth-subtitle">Crea tu cuenta y descubre el coche perfecto para ti.</p>
            <div class="features">
                <div class="feature-item mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Acceso a miles de coches</span>
                </div>
                <div class="feature-item mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Filtros avanzados de búsqueda</span>
                </div>
                <div class="feature-item mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Guarda tus coches favoritos</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Notificaciones personalizadas</span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <h2 class="auth-title">Crear Cuenta</h2>

            <div class="alert alert-danger d-none" id="ajaxErrors"></div>
            
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

            <form action="index.php?action=registrar" method="POST" id="registerForm">
                <div class="form-group">
                    <label class="form-label">Nombre Completo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="nombre" 
                               value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['nombre'] ?? ''); ?>" 
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="correo" id="correo"
                               value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['correo'] ?? ''); ?>" 
                               required>
                    </div>
                    <div class="invalid-feedback d-block" id="correoFeedback" style="display:none;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Teléfono (Opcional)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" name="telefono" 
                               value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['telefono'] ?? ''); ?>"
                               placeholder="Ej: 600123456">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="contraseña" id="contraseña" required>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="confirmar_contraseña" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Palabra clave</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="text" class="form-control" name="clave"
                               value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['clave'] ?? ''); ?>"
                               required>
                    </div>
                    <div class="form-text">La necesitarás para cambiar tu contraseña en el futuro.</div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="terminos" id="terminos" required>
                    <label class="form-check-label" for="terminos">
                        Acepto los <a href="#">términos y condiciones</a> y la <a href="#">política de privacidad</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-auth">
                    <i class="fas fa-user-plus me-2"></i>
                    Crear Cuenta
                </button>
            </form>

            <div class="auth-links">
                <p>¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión aquí</a></p>
                <p><a href="index.php">Volver al inicio</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('contraseña');
            const passwordStrength = document.getElementById('passwordStrength');

            const registerForm = document.getElementById('registerForm');
            const ajaxErrors = document.getElementById('ajaxErrors');

            const correoInput = document.getElementById('correo');
            const correoFeedback = document.getElementById('correoFeedback');
            let correoCheckTimer = null;
            let lastCorreoChecked = '';

            const setCorreoError = (msg) => {
                if (correoFeedback) {
                    correoFeedback.textContent = msg;
                    correoFeedback.style.display = msg ? 'block' : 'none';
                }
                if (correoInput) {
                    correoInput.setCustomValidity(msg ? msg : '');
                }
            };

            const checkCorreoEnUso = async (correo) => {
                const value = String(correo || '').trim();
                if (!value) {
                    setCorreoError('');
                    return;
                }

                if (!correoInput || !correoInput.checkValidity()) {
                    setCorreoError('');
                    return;
                }

                lastCorreoChecked = value;
                try {
                    const res = await fetch(`index.php?action=checkEmail&correo=${encodeURIComponent(value)}`, {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await res.json();

                    if (String(correoInput.value || '').trim() !== value) {
                        return;
                    }

                    if (data && data.ok && data.exists) {
                        setCorreoError('Este correo ya está en uso');
                    } else {
                        setCorreoError('');
                    }
                } catch (e) {
                    setCorreoError('');
                }
            };

            if (correoInput) {
                correoInput.addEventListener('input', function() {
                    const value = String(this.value || '').trim();
                    if (correoCheckTimer) clearTimeout(correoCheckTimer);
                    correoCheckTimer = setTimeout(() => checkCorreoEnUso(value), 350);
                });

                correoInput.addEventListener('blur', function() {
                    const value = String(this.value || '').trim();
                    if (value && value !== lastCorreoChecked) {
                        checkCorreoEnUso(value);
                    }
                });

                if (String(correoInput.value || '').trim()) {
                    checkCorreoEnUso(String(correoInput.value || '').trim());
                }
            }
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let message = '';
                
                if (password.length >= 6) strength++;
                if (password.length >= 10) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                if (password.length === 0) {
                    message = '';
                } else if (strength < 2) {
                    message = '<span class="strength-weak">Contraseña débil</span>';
                } else if (strength < 4) {
                    message = '<span class="strength-medium">Contraseña media</span>';
                } else {
                    message = '<span class="strength-strong">Contraseña fuerte</span>';
                }
                
                passwordStrength.innerHTML = message;
            });

            if (registerForm) {
                registerForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (correoInput && !correoInput.checkValidity()) {
                        correoInput.reportValidity();
                        return;
                    }

                    if (ajaxErrors) {
                        ajaxErrors.classList.add('d-none');
                        ajaxErrors.innerHTML = '';
                    }

                    const submitBtn = registerForm.querySelector('button[type="submit"]');
                    const prevDisabled = submitBtn ? submitBtn.disabled : false;
                    if (submitBtn) submitBtn.disabled = true;

                    try {
                        const formData = new FormData(registerForm);
                        const response = await fetch(registerForm.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            body: formData
                        });

                        const data = await response.json();

                        if (data && data.ok) {
                            window.location.href = data.redirect || 'index.php?action=login';
                            return;
                        }

                        const errors = (data && Array.isArray(data.errors)) ? data.errors : ['Error al registrarse'];
                        if (ajaxErrors) {
                            ajaxErrors.innerHTML = errors.map(err => `<div>${String(err)}</div>`).join('');
                            ajaxErrors.classList.remove('d-none');
                        }
                    } catch (err) {
                        if (ajaxErrors) {
                            ajaxErrors.innerHTML = '<div>Error de conexión. Inténtalo de nuevo.</div>';
                            ajaxErrors.classList.remove('d-none');
                        }
                    } finally {
                        if (submitBtn) submitBtn.disabled = prevDisabled;
                    }
                });
            }
        });
    </script>
</body>
</html>
