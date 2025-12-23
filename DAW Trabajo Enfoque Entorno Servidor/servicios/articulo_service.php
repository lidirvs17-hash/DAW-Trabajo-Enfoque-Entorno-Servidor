<?php

require_once __DIR__ . '/../datos/articulo_dao.php';
/**
 * Función para obtener todos los artículos.
 * @param array $filtros Array asociativo con filtros recibidos (opcional).
 * @return array Devuelve un array con todos los artículos.
 */
function obtenerCatalogo($filtros = []) {
    $articulos = getAllArticulos($filtros); 
    
    // Adjuntar tallas disponibles a cada artículo
    foreach ($articulos as &$articulo) { // Usamos & (referencia) para modificar el array original
        $articulo['tallas'] = getArticuloTallas($articulo['id_articulo']);
    }
    unset($articulo); // Buena práctica: romper la referencia
    return $articulos;
}

function crearArticulo($nombre, $descripcion, $precio, $imagen, $genero, $categoria, $variantes) {
    // 1. Validaciones básicas:
    if (empty($nombre) || !is_numeric($precio) || $precio <= 0 || empty($genero) || empty($categoria)) {
        return false;
    }

    // 2. Insertar el artículo principal y obtener el ID
    // Quitamos $stock del DAO
    $id_articulo = insertArticulo($nombre, $descripcion, $precio, $imagen, $genero, $categoria);
    
    if (!$id_articulo) {
        error_log("Fallo al insertar el artículo principal.");
        return false; 
    }

    // 3. Insertar las tallas
    foreach ($variantes as $talla => $stock) {
        $stock = (int)$stock;
       
        if (!empty($talla) && $stock >= 0) { 
            if (!insertArticuloTalla($id_articulo, $talla, $stock)) {
                 
                error_log("Fallo al insertar la variante de talla $talla para el artículo $id_articulo");
                
            }
        }
    }
    return true;
}
/**
 * Procesa la actualización de un artículo, validando los datos.
 * @return bool True si la actualización es exitosa.
 */
function actualizarArticulo($id, $nombre, $descripcion, $precio, $imagen, $genero, $categoria) {

    if (!is_numeric($id) || $id <= 0 || empty($nombre) || !is_numeric($precio) || $precio <= 0) {
        return false;
    }

    return updateArticulo($id, $nombre, $descripcion, $precio, $imagen, $genero, $categoria);
}

/**
 * Procesa la eliminación de un artículo.
 * @param int $id ID del artículo a eliminar.
 * @return bool True si la eliminación es exitosa.
 */
function eliminarArticulo($id) {

    if (!is_numeric($id) || $id <= 0) {
        return false;
    }
    
    return deleteArticulo($id);
}
function obtenerArticuloPorId($id) {
return getArticuloById($id);
}
?>