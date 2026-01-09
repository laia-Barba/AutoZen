<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - AutoZen</title>
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
        }

        .main-content {
            margin-top: 60px;
            padding: 40px 0;
        }

        .card-custom {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .btn-secondary-custom {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 10px 18px;
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

        .table thead th {
            background: var(--light-bg);
        }

        .badge-admin {
            background: rgba(255, 107, 53, 0.15);
            color: #b3441f;
            border: 1px solid rgba(255, 107, 53, 0.35);
        }

        .badge-user {
            background: rgba(0, 78, 137, 0.12);
            color: #004E89;
            border: 1px solid rgba(0, 78, 137, 0.3);
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <a href="index.php?action=admin" class="btn btn-secondary-custom">
        <i class="fas fa-arrow-left me-2"></i>
        Volver al Panel
    </a>
</div>

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

        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="m-0"><i class="fas fa-users me-2"></i>Gestionar Usuarios</h3>
                <span class="text-muted">Total: <?php echo isset($usuarios) ? count($usuarios) : 0; ?></span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th style="width: 220px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay usuarios para mostrar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$u['idUsuario']); ?></td>
                                <td><?php echo htmlspecialchars((string)$u['Nombre']); ?></td>
                                <td><?php echo htmlspecialchars((string)$u['Correo']); ?></td>
                                <td><?php echo htmlspecialchars((string)($u['Telefono'] ?? '')); ?></td>
                                <td>
                                    <?php if ((int)$u['isAdmin'] === 1): ?>
                                        <span class="badge badge-admin">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge badge-user">Usuario</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $idActual = (int)($usuarioActual['idUsuario'] ?? 0);
                                        $esMismo = $idActual > 0 && (int)$u['idUsuario'] === $idActual;
                                    ?>

                                    <?php if ($esMismo): ?>
                                        <span class="text-muted">No editable</span>
                                    <?php else: ?>
                                        <form action="index.php?action=toggleUserAdmin" method="POST" class="d-inline">
                                            <input type="hidden" name="idUsuario" value="<?php echo htmlspecialchars((string)$u['idUsuario']); ?>">
                                            <input type="hidden" name="isAdmin" value="<?php echo ((int)$u['isAdmin'] === 1) ? '0' : '1'; ?>">

                                            <?php if ((int)$u['isAdmin'] === 1): ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Quitar admin
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    Hacer admin
                                                </button>
                                            <?php endif; ?>
                                        </form>

                                        <form action="index.php?action=deleteUser" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres borrar este usuario?');">
                                            <input type="hidden" name="idUsuario" value="<?php echo htmlspecialchars((string)$u['idUsuario']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Borrar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
