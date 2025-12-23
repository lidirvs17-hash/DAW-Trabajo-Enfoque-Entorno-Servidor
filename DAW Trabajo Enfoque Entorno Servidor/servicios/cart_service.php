<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function initCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function getCart() {
    initCart();
    return $_SESSION['cart'];
}

/**
* Agrega un artículo al carrito
* @param int $id_articulo ID del artículo a agregar
* @param int $cantidad Cantidad a agregar  por defecto es 1
*/
function addToCart($articulo_variante, $cantidad = 1) {
    initCart();
    $id_variante = $articulo_variante['id_variante'];
    $stock_disponible = $articulo_variante['stock']; 

    $cantidad_actual = isset($_SESSION['cart'][$id_variante]) ? $_SESSION['cart'][$id_variante]['cantidad'] : 0;

    if (($cantidad_actual + $cantidad) > $stock_disponible) {
        $mensaje = 'No hay suficiente stock disponible para la cantidad solicitada.';
        return false; 
    }

    if (isset($_SESSION['cart'][$id_variante])) {
        $_SESSION['cart'][$id_variante]['cantidad'] += $cantidad;
    } else {
        $_SESSION['cart'][$id_variante] = [
            'articulo' => $articulo_variante,
            'cantidad' => $cantidad
        ];
    }
    return true;
}
/** Elimina un artículo del carrito
* @param int $id_articulo ID del artículo a eliminar
*/
function removeFromCart($id_articulo) {
    initCart();
    if (isset($_SESSION['cart'][$id_articulo])) {
        unset($_SESSION['cart'][$id_articulo]);
    }
}

/** Actualiza la cantidad de un artículo en el carrito
* @param int $id_articulo ID del artículo a actualizar  
* @param int $new_quantity Nueva cantidad
*/ 
function updateCartItemQuantity($id_articulo, $new_quantity) {
    initCart();
    if (isset($_SESSION['cart'][$id_articulo]) && is_numeric($new_quantity) && $new_quantity > 0) {
        $_SESSION['cart'][$id_articulo]['cantidad'] = (int)$new_quantity;
    } elseif ($new_quantity <= 0) {
        removeFromCart($id_articulo); // Eliminar si la cantidad es 0 o menos
    }
}

// =========================================================
// Funciones de Cálculo
// =========================================================


function calculateItemSubtotal($item) {
    return $item['articulo']['precio'] * $item['cantidad'];
}


function calculateCartTotal() {
    initCart();
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += calculateItemSubtotal($item);
    }
    return $total;
}
/**
 * Calcula la cantidad total de productos en el carrito (no ítems únicos).
 * @return int Cantidad total de productos.
 */
function calculateTotalProductQuantity() {
    initCart();
    $total_qty = 0;
    foreach ($_SESSION['cart'] as $item) {
        // Sumamos la cantidad de cada ítem
        $total_qty += $item['cantidad'];
    }
    return $total_qty;
}
?>