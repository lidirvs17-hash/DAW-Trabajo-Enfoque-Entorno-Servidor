<?php
// 1. Gestión de sesión única
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Carga de dependencias con rutas absolutas
require_once __DIR__ . '/servicios/cart_service.php';
require_once __DIR__ . '/servicios/user_service.php';

// 3. Inicialización de datos de usuario y carrito
initCart();
$cart_count   = calculateTotalProductQuantity();
$is_logged_in = isset($_SESSION['user_id']);
$user_name    = $is_logged_in ? htmlspecialchars($_SESSION['user_nombre']) : 'Invitado';
$user_rol     = $is_logged_in ? strtolower($_SESSION['user_rol']) : '';


$base_url = '/LRVS/'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'CROSS-KICKS'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="<?php echo $base_url; ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $base_url; ?>index.php">
                <i class="fas fa-bolt me-2"></i> CROSS-KICKS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>catalogo.php">
                            <i class="fas fa-shoe-prints me-1"></i> Catálogo
                        </a>
                    </li>
                    <?php if ($user_rol === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="<?php echo $base_url; ?>gestion_articulos.php">
                                <i class="fas fa-tools me-1"></i> Gestión (Admin)
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="<?php echo $base_url; ?>carrito.php">
                            <i class="fas fa-shopping-cart me-1"></i> Carrito (<?php echo $cart_count; ?>)
                        </a>
                    </li>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> Hola, <?php echo $user_name; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($user_rol === 'admin'): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo $base_url; ?>gestion_usuarios.php">
                                            <i class="fas fa-users-cog me-2"></i> Gestión de Usuarios
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_url; ?>mi_perfil.php">
                                        <i class="fas fa-id-card me-2"></i> Mi Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_url; ?>historial_pedidos.php">
                                        <i class="fas fa-history me-2"></i> Mis Pedidos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?php echo $base_url; ?>logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="<?php echo $base_url; ?>login.php">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container mt-5 pt-4">