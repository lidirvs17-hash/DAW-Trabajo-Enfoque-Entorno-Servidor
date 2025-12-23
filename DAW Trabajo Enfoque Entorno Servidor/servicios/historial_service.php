<?php
require_once __DIR__ . '/../datos/pedido_dao.php';
/**
 * Obtiene el historial completo de pedidos para un usuario, incluyendo detalles.
 */
function getHistorialCompleto($id_usuario) {
    $pedidos = getPedidosByUserId($id_usuario);
    
    foreach ($pedidos as &$pedido) {
        // Adjuntar los detalles a cada pedido
        $pedido['detalles'] = getDetallesPedido($pedido['id_pedido']);
    }
    unset($pedido); // Romper la referencia
    return $pedidos;
}
?>