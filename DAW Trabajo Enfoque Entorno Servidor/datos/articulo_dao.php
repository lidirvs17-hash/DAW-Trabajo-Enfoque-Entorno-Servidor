<?php
require_once 'db_connection.php';

/**
 * Función para obtener un artículo por su ID. (READ)
 * @param int $id ID del artículo.
 * @return array|false Devuelve el array del artículo si se encuentra, o false.
 */
function getArticuloById($id) {
    $conn = connectDB();
    if (!$conn) {
        return false;
    }

    $sql = "SELECT id_articulo, nombre, descripcion, precio, imagen, genero, categoria FROM articulos WHERE id_articulo = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $articulo = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    return $articulo;
}

/**
 * Función para obtener todos los artículos. (READ)
 * La lógica de filtros es correcta y lista para usarse con las nuevas categorías.
 * @param array $filtros Array asociativo con filtros recibidos.
 * @return array Devuelve un array con todos los artículos.
 */ 
function getAllArticulos($filtros = []) {
    $conn = connectDB();
    if (!$conn) {
        return [];
    }

    $sql = "SELECT id_articulo, nombre, descripcion, precio, imagen, genero, categoria FROM articulos";
    $where_clauses = [];
    $tipos = '';
    $params = [];
    
    // Filtro por CATEGORÍA 
    if (!empty($filtros['categoria'])) {
        $where_clauses[] = "categoria = ?";
        $tipos .= 's';
        $params[] = $filtros['categoria'];
    }
    
    // Filtro por PRECIO MÁXIMO 
    if (isset($filtros['precio_max']) && is_numeric($filtros['precio_max']) && $filtros['precio_max'] > 0) {
        $where_clauses[] = "precio <= ?";
        $tipos .= 'd'; // 'd' para double/decimal
        $params[] = (float)$filtros['precio_max']; 
    }
    
    // ... (Se pueden añadir más filtros aquí: género, etc.)

    if (count($where_clauses) > 0) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $sql .= " ORDER BY nombre ASC";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $conn->close();
        return [];
    }

    // Enlazar parámetros solo si existen
    if (count($params) > 0) {
        $stmt->bind_param($tipos, ...$params); 
    }

    $stmt->execute();
    $result = $stmt->get_result();
    
    $articulos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articulos[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $articulos;
}

/**
 * Función para insertar un nuevo artículo. (CREATE)
 * @param string $nombre Nombre del artículo.
 * @param string $descripcion Descripción del artículo.
 * @param float $precio Precio del artículo.
 * @param string $imagen URL o ruta de la imagen del artículo.
 * @param string $genero.
 * @param string $categoria.
 * @return int|false Devuelve el ID del nuevo artículo si es exitoso, false si hay un error.
 */
function insertArticulo($nombre, $descripcion, $precio, $imagen, $genero, $categoria) {
    $conn = connectDB();
    if (!$conn) return false;

    // Solo insertamos las columnas del artículo, el stock va a otra tabla
    $sql = "INSERT INTO articulos (nombre, descripcion, precio, imagen, genero, categoria) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Asumiendo que imagen es string (s) o podrías usar blob (b) si es el contenido binario. 
    // Usamos 's' para la ruta/URL
    $stmt->bind_param("sdsiss", $nombre, $descripcion, $precio, $imagen, $genero, $categoria);

    $success = $stmt->execute();
    
    $new_id = $conn->insert_id;

    $stmt->close();
    $conn->close();
    
    return $success ? $new_id : false; // Devolver el ID o false
}

/**
 * Función para actualizar un artículo existente. (UPDATE)
 * @param int $id ID del artículo.
 * @return bool true si la actualización es exitosa, false si hay un error.
 */
function updateArticulo($id, $nombre, $descripcion, $precio, $imagen, $genero, $categoria) {
    $conn = connectDB();
    if (!$conn) {
        return false;
    }
    
    $sql = "UPDATE articulos SET 
              nombre = ?, descripcion = ?, precio = ?, imagen = ?, genero = ?, categoria = ? 
            WHERE id_articulo = ?";
    
    $stmt = $conn->prepare($sql);
    
 
    $stmt->bind_param("ssdsisi", $nombre, $descripcion, $precio, $imagen, $genero, $categoria, $id);
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $success;
}

/**
 * Función para eliminar un artículo. (DELETE)
 * @param int $id ID del artículo.
 * @return bool true si la eliminación es exitosa, false si hay un error.
 */
function deleteArticulo($id) {
    $conn = connectDB();
    if (!$conn) {
        return false;
    }


    $sql = "DELETE FROM articulos WHERE id_articulo = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $success;
}

/**
 * Obtiene el stock actual de un artículo mapeado por talla.
 * Esto es para precargar el formulario de Admin.
 * @param int $id_articulo ID del artículo.
 * @return array Array asociativo [talla => stock]
 */
function getStockMapByArticuloId($id_articulo) {
    $conn = connectDB();
    if (!$conn) return [];
    
    $sql = "SELECT talla, stock FROM articulo_talla WHERE id_articulo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stock_map = [];
    while ($row = $result->fetch_assoc()) {
        $stock_map[$row['talla']] = $row['stock']; 
    }
    $stmt->close();
    $conn->close();
    return $stock_map;
}


function updateArticuloTallaStock($id_articulo, $talla, $stock) {
    $conn = connectDB();
    if (!$conn) return false;

    
    $sql = "INSERT INTO articulo_talla (id_articulo, talla, stock) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE stock = VALUES(stock)"; 
    
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $id_articulo, $talla, $stock); 

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}


function getArticuloTallas($id_articulo) {
    $conn = connectDB();
    if (!$conn) return [];
    
    $sql = "SELECT talla, stock FROM articulo_talla WHERE id_articulo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $tallas = [];
    while ($row = $result->fetch_assoc()) {
        $tallas[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $tallas;
}

function insertArticuloTalla($id_articulo, $talla, $stock) {
    $conn = connectDB();
    if (!$conn) return false;

    $sql = "INSERT INTO articulo_talla (id_articulo, talla, stock) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("isi", $id_articulo, $talla, $stock); 

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}
?>