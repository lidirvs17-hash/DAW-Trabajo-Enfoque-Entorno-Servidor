<?php
session_start();
require_once 'servicios/cart_service.php';

initCart();

// =========================================================
// Lógica de Modificación del Carrito (DELETE)
// =========================================================
$delete_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_SPECIAL_CHARS);
if (!empty($delete_id)) {
    removeFromCart($delete_id);
    header('Location: carrito.php?msg=' . urlencode('Artículo eliminado del carrito.'));
    exit;
}

// =========================================================
// Lógica de Modificación del Carrito (UPDATE)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

    if (!empty($id) && $qty !== null) { 
        updateCartItemQuantity($id, $qty);
        $mensaje_exito = 'Cantidad actualizada.';
    }
    header('Location: carrito.php?msg=' . urlencode($mensaje_exito ?? 'Error al actualizar.'));
    exit;
}

// =========================================================
// Carga de Datos para la Vista
// =========================================================
$cart_items = getCart();
$cart_total = calculateCartTotal();
$mensaje = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
$is_logged_in = isset($_SESSION['user_id']);

$checkout_url = $is_logged_in ? 'checkout.php' : 'login.php?redirect=checkout.php&msg=' . urlencode('Debes iniciar sesión para pagar.');

$page_title = 'Tu Carrito | CROSS-KICKS';
require_once 'header.php';
?>

<div class="container py-5">
    <h1 class="mb-4 fw-bold" style="color: var(--ck-dark-green);">TU CARRITO</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i> <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <?php if (count($cart_items) > 0): ?>
        <div class="glass-panel shadow-sm p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr style="color: var(--ck-dark-green); border-bottom: 2px solid var(--ck-primary);">
                            <th>Producto</th>
                            <th>Talla</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $id_variante => $item): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($item['articulo']['nombre']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['articulo']['talla_seleccionada']); ?></span></td>
                            <td><?php echo number_format($item['articulo']['precio'], 2); ?> €</td>
                            <td>
                                <form action="carrito.php" method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="id" value="<?php echo $id_variante; ?>">
                                    <input type="number" name="qty" value="<?php echo $item['cantidad']; ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" name="update_qty" class="btn btn-sm btn-outline-primary" title="Actualizar">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="fw-bold"><?php echo number_format(calculateItemSubtotal($item), 2); ?> €</td>
                            <td class="text-center">
                                <a href="carrito.php?delete_id=<?php echo urlencode($id_variante); ?>" 
                                   class="btn btn-outline-danger btn-sm" 
                                   onclick="return confirm('¿Eliminar este par del carrito?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="4" class="text-end fw-bold fs-5">TOTAL COMPRA:</td>
                            <td class="fw-bold fs-5 text-primary" colspan="2"><?php echo number_format($cart_total, 2) . ' €'; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                <a href="catalogo.php" class="btn btn-secondary px-4 fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> SEGUIR EXPLORANDO
                </a>
                <a href="<?php echo $checkout_url; ?>" class="btn btn-primary btn-lg px-5 fw-bold shadow">
                    FINALIZAR PEDIDO <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        </div>

    <?php else: ?>
        <div class="glass-panel text-center py-5 shadow-sm">
            <i class="fas fa-shopping-cart fa-4x mb-4 text-muted opacity-25"></i>
            <h3 class="text-muted">Tu carrito está vacío</h3>
            <p class="mb-4">Parece que aún no has encontrado el loot adecuado para tu misión.</p>
            <a href="catalogo.php" class="btn btn-primary btn-lg px-5">IR AL CATÁLOGO</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>