<?php
require_once 'db_connection.php'; 

/**
 * Obtiene todos los pedidos realizados por un usuario.
 */
function getPedidosByUserId($id_usuario) {
    $conn = connectDB();
    if (!$conn) return [];

    $sql = "SELECT id_pedido, fecha_pedido, total, estado_pedido FROM pedido WHERE id_usuario = ? ORDER BY fecha_pedido DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $pedidos = [];
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $pedidos;
}

/**
 * Obtiene el detalle de los artículos de un pedido específico.
 */
function getDetallesPedido($id_pedido) {
    $conn = connectDB();
    if (!$conn) return [];
    
$sql = "SELECT dp.cantidad, dp.precio_unitario, dp.talla, a.nombre 
            FROM detalle_pedido dp
            JOIN articulos a ON dp.id_articulo = a.id_articulo
            WHERE dp.id_pedido = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();

    $detalles = [];
    while ($row = $result->fetch_assoc()) { $detalles[] = $row; }
    $stmt->close();
    $conn->close();
    return $detalles;
}
?>
