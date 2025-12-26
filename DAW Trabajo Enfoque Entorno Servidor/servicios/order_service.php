<?php
require_once __DIR__ . '/../datos/order_dao.php';
require_once __DIR__ . '/cart_service.php';

/**
 * Procesa el pedido del usuario, guarda en la BD y limpia el carrito.
 * @param int $id_usuario ID del cliente logueado. 
 * @param array $datos_envio Array con direccion, ciudad y cp.
 * @return bool True si la compra fue exitosa.
*/
function processCheckout($id_usuario, $datos_envio) { 

    // 1. Extraer y validar datos de envío
    $direccion = $datos_envio['direccion'] ?? '';
    $ciudad = $datos_envio['ciudad'] ?? '';
    $cp = $datos_envio['cp'] ?? '';

    if (empty($direccion) || empty($ciudad) || empty($cp)) {
        return false; 
    }

    // 2. Inicializar carrito y obtener datos
    initCart();
    $cart_items = getCart();
    
    // 3. Validaciones de Negocio
    if (empty($id_usuario) || empty($cart_items)) {
        return false; 
    }
    
    $total = calculateCartTotal();

    // 4. Iniciar Transacción en el DAO 
    $success = saveOrderTransaction($id_usuario, $total, $cart_items, $direccion, $ciudad, $cp);

    if ($success) {
        // 5. Limpiar carrito de la sesión tras el éxito
        unset($_SESSION['cart']); 
        return true;
    } 
    
    return false;
}
?>