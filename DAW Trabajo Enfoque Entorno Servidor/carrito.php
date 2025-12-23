<?php
session_start();
require_once 'servicios/cart_service.php';

initCart();
$cart_items = getCart();
$cart_total = calculateCartTotal();

$mensaje = '';

// =========================================================
// Lógica de Modificación del Carrito (DELETE)
// =========================================================
$delete_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_SPECIAL_CHARS);
if (!empty($delete_id)) {
    removeFromCart($delete_id);
    $mensaje = 'Artículo eliminado del carrito.';
     
    header('Location: /LRVS/carrito.php?msg=' . urlencode($mensaje));
    exit;
}

// =========================================================
// Lógica de Modificación del Carrito (UPDATE)
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
    $mensaje = ''; // Inicializar para el POST

    if (!empty($id) && $qty !== null) { 
        updateCartItemQuantity($id, $qty);
        $mensaje = 'Cantidad actualizada.';
    } else {
        $mensaje = 'Error al actualizar: ID o cantidad no válidos.';
    }

    header('Location: /LRVS/carrito.php?msg=' . urlencode($mensaje));
    exit;
}


// =========================================================
// Lógica de Visualización del Carrito (GET)
// =========================================================

// Recargar el carrito y total
$cart_items = getCart();
$cart_total = calculateCartTotal();
 
$msg_url = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
if ($msg_url) {
    $mensaje = $msg_url;
}


$is_logged_in = isset($_SESSION['user_id']);


if ($is_logged_in) {
    $checkout_url = '/LRVS/checkout.php';
} else {
    
    $checkout_url = 'login.php?redirect=' . urlencode('checkout.php') . '&msg=' . urlencode('Debes iniciar sesión para pagar.');
}

$page_title = 'Tu Carrito de Compras';
require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras | Tienda</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .total-row td { font-weight: bold; background-color: #f9f9f9; }
        .checkout-button { background-color: #28a745; color: white; padding: 15px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <h1 class="mb-4">Tu Carrito</h1>

<?php if ($mensaje): ?>
    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($mensaje); ?></div>
<?php endif; ?>

<?php if (count($cart_items) > 0): ?>
   <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Producto</th>
            <th>Talla</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cart_items as $id_variante => $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['articulo']['nombre']); ?></td>
            <td><?php echo htmlspecialchars($item['articulo']['talla_seleccionada']); ?></td>
            <td><?php echo number_format($item['articulo']['precio'], 2); ?> €</td>
            <td>
                <form action="carrito.php" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?php echo $id_variante; ?>">
                    <input type="number" name="qty" value="<?php echo $item['cantidad']; ?>" min="1" style="width: 60px;">
                    <button type="submit" name="update_qty" class="btn btn-sm btn-outline-primary">Actualizar</button>
                </form>
            </td>
            <td><?php echo number_format(calculateItemSubtotal($item), 2); ?> €</td>
            <td>
                <a href="carrito.php?delete_id=<?php echo urlencode($id_variante); ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tr class="table-info">
        <td colspan="4" class="text-end fw-bold">Total de la Compra:</td>
        <td class="fw-bold fs-5" colspan="2"><?php echo number_format($cart_total, 2) . ' €'; ?></td>
    </tr>
</table>
    
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="<?php echo $base_url; ?>catalogo.php" class="btn btn-secondary">Continuar Comprando</a>
        <a href="<?php echo $checkout_url; ?>" class="btn btn-success btn-lg">Pagar y Finalizar Compra</a>
    </div>

<?php else: ?>
    <div class="alert alert-warning" role="alert">
        Tu carrito está vacío. ¡Es hora de ir de compras!
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>