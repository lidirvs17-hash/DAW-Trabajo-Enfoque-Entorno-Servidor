<?php
session_start();

require_once 'servicios/cart_service.php';
require_once 'servicios/articulo_service.php';

$base_url = '/LRVS/';

//1 Si no hay sesion de usuario, redirigir al login
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $base_url . 'login.php');
    exit;
}

// 2. Obtener ID del artículo, cantidad y talla
$id_articulo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$cantidad = filter_input(INPUT_GET, 'qty', FILTER_VALIDATE_INT) ?: 1;
$talla = filter_input(INPUT_GET, 'talla', FILTER_SANITIZE_SPECIAL_CHARS);

if ($id_articulo && $talla) {
    $articulo_base = obtenerArticuloPorId($id_articulo); 

    if ($articulo_base) {
        $variante_id = $id_articulo . '-' . $talla;
        $articulo_variante = $articulo_base;
        $articulo_variante['talla_seleccionada'] = $talla;
        $articulo_variante['id_variante'] = $variante_id;
        
        // 3. Agregar al carrito (Usando la variante ID y la info de la variante)
        addToCart($articulo_variante, $cantidad);
        $mensaje = urlencode('Artículo ' . $articulo_base['nombre'] . ' (Talla: ' . $talla . ') añadido.');
    } else {
        $mensaje = urlencode('Error: Artículo no encontrado.');
    }
} else {
    $mensaje = urlencode('Error: Faltan el ID o la Talla.');
}
// 4. Redirigir de vuelta al catálogo o página anterior
$referrer = $_SERVER['HTTP_REFERER'] ?? '/LRVS/catalogo.php';
// Eliminar parámetros de consulta para evitar duplicados
$base_referrer = strtok($referrer, '?'); 

header('Location: /LRVS/catalogo.php?msg=' . $mensaje);
exit;
?>