<?php
session_start();
require_once 'servicios/articulo_service.php'; 

// Control de Acceso: SOLO ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'Admin') {
    header('Location: index.php'); 
    exit;
}


$id_articulo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id_articulo) {
  
    if (eliminarArticulo($id_articulo)) {
        $mensaje = 'Artículo eliminado con éxito.';
    } else {
        $mensaje = 'Error al eliminar el artículo.';
    }
} else {
    $mensaje = 'ID de artículo no válido.';
}


header('Location: /LRVS/Tienda_zapatillas/gestion_articulos.php?msg=' . urlencode($mensaje));
exit;
?>