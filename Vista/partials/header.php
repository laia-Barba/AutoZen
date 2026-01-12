<?php
// Variables should be passed from including page
// $estaLogueado, $usuarioActual, $esAdmin should be available
?>
<!-- Navegación -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-car"></i> AutoZen
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=buscar">Coches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#destacados">Destacados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contacto">Contacto</a>
                </li>
                
                <li class="nav-item">
                    <div class="user-menu-container">
                        <button class="btn btn-link nav-link user-icon-btn" id="userMenuBtn">
                            <i class="fas fa-user"></i>
                            <?php if (isset($estaLogueado) && $estaLogueado): ?>
                                <span class="user-name ms-2"><?php echo htmlspecialchars($usuarioActual['Nombre']); ?></span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Popup Menu -->
                        <div class="user-popup" id="userPopup">
                            <?php if (isset($estaLogueado) && $estaLogueado): ?>
                                <div class="popup-header">
                                    <i class="fas fa-user-circle"></i>
                                    <span><?php echo htmlspecialchars($usuarioActual['Nombre']); ?></span>
                                </div>
                                <div class="popup-actions">
                                    <a href="index.php?action=perfil" class="popup-action">
                                        <i class="fas fa-user"></i>
                                        <span>Ver Perfil</span>
                                    </a>
                                    <a href="index.php?action=carrito" class="popup-action">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Carrito</span>
                                    </a>
                                    <?php if (isset($esAdmin) && $esAdmin): ?>
                                        <a href="index.php?action=admin" class="popup-action">
                                            <i class="fas fa-cog"></i>
                                            <span>Administración</span>
                                        </a>
                                        <a href="index.php?action=addCar" class="popup-action">
                                            <i class="fas fa-plus-circle"></i>
                                            <span>Añadir Coches</span>
                                        </a>
                                    <?php endif; ?> 
                                    <a href="index.php?action=logout" class="popup-action logout">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Cerrar Sesión</span>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="popup-header">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Mi Cuenta</span>
                                </div>
                                <div class="popup-actions">
                                    <a href="index.php?action=login" class="popup-action">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Iniciar Sesión</span>
                                    </a>
                                    <a href="index.php?action=registro" class="popup-action">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Registrarse</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
            </ul>
        </div>
    </div>
</nav>
