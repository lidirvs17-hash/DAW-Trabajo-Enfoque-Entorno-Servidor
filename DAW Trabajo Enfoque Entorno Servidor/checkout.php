<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'servicios/order_service.php'; 
require_once 'servicios/cart_service.php'; 

// 1. Control de Acceso
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?msg=' . urlencode('Debes iniciar sesión para finalizar la compra.'));
    exit;
}

initCart();
$carrito = getCart();

if (empty($carrito)) {
    header('Location: carrito.php?msg=' . urlencode('El carrito está vacío.'));
    exit;
}

$exito = false;
$procesado = false;
$mensaje = '';

// 2. Lógica de Procesamiento (Solo ocurre al pulsar el botón del formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_pago'])) {
    $datos = [
        'direccion' => $_POST['direccion'],
        'ciudad'    => $_POST['ciudad'],
        'cp'        => $_POST['cp']
    ];

    if (processCheckout($_SESSION['user_id'], $datos)) {
        $mensaje = '¡Su pedido ha sido procesado con éxito! Tu equipo está de camino.';
        $exito = true;
    } else {
        $mensaje = 'Error crítico: No se pudo finalizar la compra. Revisa los datos o el stock disponible.';
        $exito = false;
    }
    $procesado = true;
}

$page_title = "Finalizar Compra | CROSS-KICKS";
require_once 'header.php'; 
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            
            <?php if (!$procesado): ?>
                <div class="glass-panel p-5 shadow-lg" style="border: 2px solid var(--ck-primary); border-radius: 20px; background: rgba(20, 20, 20, 0.8); text-align: left;">
                    <h2 class="text-white fw-bold mb-4 text-center">DATOS DE ENTREGA</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-white">Dirección Completa</label>
                            <input type="text" name="direccion" class="form-control bg-dark text-white border-secondary" required placeholder="Calle, número, piso...">
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label text-white">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control bg-dark text-white border-secondary" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label text-white">Código Postal</label>
                                <input type="text" name="cp" class="form-control bg-dark text-white border-secondary" required pattern="[0-9]{5}">
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="confirmar_pago" class="btn btn-primary btn-lg fw-bold" style="background-color: #9f59f9; border: none;">
                                <i class="fas fa-check-circle me-2"></i>CONFIRMAR Y FINALIZAR PEDIDO
                            </button>
                        </div>
                    </form>
                </div>

            <?php else: ?>
                <div class="glass-panel p-5 shadow-lg" style="border: 2px solid var(--ck-primary); border-radius: 20px; background: rgba(20, 20, 20, 0.8);">
                    <?php if ($exito): ?>
                        <div class="order-success-icon mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem; filter: drop-shadow(0 0 10px #28a745);"></i>
                        </div>
                        <h1 class="display-5 fw-bold text-white mb-3">¡MISIÓN COMPLETADA!</h1>
                        <p class="fs-5 text-muted mb-4"><?php echo htmlspecialchars($mensaje); ?></p>
                        <a href="historial_pedidos.php" class="btn btn-primary btn-lg px-4" style="background-color: #9f59f9; border: none;">Mis Pedidos</a>
                    <?php else: ?>
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h1 class="display-5 fw-bold text-white mb-3">ERROR EN EL PEDIDO</h1>
                        <p class="fs-5 text-danger mb-4"><?php echo htmlspecialchars($mensaje); ?></p>
                        <a href="carrito.php" class="btn btn-warning btn-lg px-4">Volver al Carrito</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>