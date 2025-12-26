<?php
session_start();
require_once 'servicios/cart_service.php';
require_once 'servicios/articulo_service.php';

// Capturamos los datos del catálogo (GET)
$id_articulo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$talla = filter_input(INPUT_GET, 'talla', FILTER_SANITIZE_SPECIAL_CHARS);
$qty = filter_input(INPUT_GET, 'qty', FILTER_VALIDATE_INT) ?? 1;

if ($id_articulo && $talla) {
    // Buscamos la información completa del artículo para guardarla en la sesión
    $articulo = obtenerArticuloPorId($id_articulo);
    
    if ($articulo) {
        // Añadimos la talla seleccionada al array del artículo
        $articulo['talla_seleccionada'] = $talla;
        
        // Buscamos el stock específico de esa talla
        $stock_disponible = 0;
        foreach ($articulo['tallas'] as $variante) {
            if ($variante['talla'] == $talla) {
                $stock_disponible = $variante['stock'];
                break;
            }
        }

        // Preparamos el objeto variante para el servicio
        $articulo_variante = [
            'id_variante' => $id_articulo . "_" . $talla, // ID único: producto + talla
            'id_articulo' => $id_articulo,
            'nombre' => $articulo['nombre'],
            'precio' => $articulo['precio'],
            'talla_seleccionada' => $talla,
            'stock' => $stock_disponible
        ];

        if (addToCart($articulo_variante, $qty)) {
            header('Location: catalogo.php?msg=' . urlencode('¡Añadido al inventario!'));
        } else {
            header('Location: catalogo.php?msg=' . urlencode('Error: No hay suficiente stock.'));
        }
    } else {
        header('Location: catalogo.php?msg=' . urlencode('Error: Artículo no encontrado.'));
    }
} else {
    header('Location: catalogo.php?msg=' . urlencode('Error: Selecciona una talla.'));
}
exit;