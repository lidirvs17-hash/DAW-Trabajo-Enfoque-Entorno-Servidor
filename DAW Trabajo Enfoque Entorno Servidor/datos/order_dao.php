<?php
require_once 'db_connection.php';

function saveOrderTransaction($id_usuario, $total, $detalles, $direccion, $ciudad, $cp) {
    $conn = connectDB();
    if (!$conn) return false;

    $conn->begin_transaction();

    try {
        // 1. Insertar el Pedido
        $sql_pedido = "INSERT INTO pedido (id_usuario, fecha_pedido, total, direccion, ciudad, cp) VALUES (?, NOW(), ?, ?, ?, ?)";
        $stmt_pedido = $conn->prepare($sql_pedido);
        $stmt_pedido->bind_param("idsss", $id_usuario, $total, $direccion, $ciudad, $cp);
        
        if (!$stmt_pedido->execute()) {
            throw new Exception("Error al crear el pedido principal.");
        }

        $id_pedido = $conn->insert_id;
        $stmt_pedido->close();

        // 2. Insertar Detalles y Actualizar Stock
        $sql_detalle = "INSERT INTO detalle_pedido (id_pedido, id_articulo, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmt_detalle = $conn->prepare($sql_detalle);

        $sql_stock = "UPDATE articulo_talla SET stock = stock - ? WHERE id_articulo = ? AND talla = ?";
        $stmt_stock = $conn->prepare($sql_stock);

        foreach ($detalles as $item) {
            $id_articulo = $item['articulo']['id_articulo'];
            $cantidad = $item['cantidad'];
            $precio_unidad = $item['articulo']['precio'];
            $talla = $item['articulo']['talla_seleccionada'];

            // Guardar detalle
            $stmt_detalle->bind_param("iidd", $id_pedido, $id_articulo, $cantidad, $precio_unidad);
            if (!$stmt_detalle->execute()) throw new Exception("Error en detalle");

            // Actualizar stock
            $stmt_stock->bind_param("iis", $cantidad, $id_articulo, $talla);
            if (!$stmt_stock->execute()) throw new Exception("Error en stock");
        }

        $stmt_detalle->close();
        $stmt_stock->close();
        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error en la transacción: " . $e->getMessage());
        return false;
    } finally {
        $conn->close();
    }
}