<?php
session_start();
require_once 'servicios/articulo_service.php';



if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'Admin') {
    header('Location: index.php'); 
    exit;
}

$id_articulo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$articulo = null;
$mensaje = '';

// 1. PROCESO POST: Guardar cambios (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    $imagen = filter_input(INPUT_POST, 'imagen', FILTER_SANITIZE_URL);
    $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_SPECIAL_CHARS);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($id && actualizarArticulo($id, $nombre, $descripcion, $precio, $stock, $imagen, $genero, $categoria)) {
        // Redirigir a gestión para ver los cambios
        header('Location: /LRVS/Tienda_zapatillas/gestion_articulos.php?msg=' . urlencode('Artículo actualizado con éxito.'));
        exit;
    } else {
        $mensaje = '<p style="color:red;">Error al actualizar el artículo.</p>';
        // Recargar el artículo actual para que el admin pueda seguir editando
        $articulo = getArticuloById($id); 
    }
} 
// 2. PROCESO GET: Mostrar formulario con datos actuales
else if ($id_articulo) {
    // getArticuloById() está en el DAO
    $articulo = getArticuloById($id_articulo); 
    if (!$articulo) {
        $mensaje = '<p style="color:red;">Artículo no encontrado.</p>';
        $id_articulo = null;
    }
} else {
    $mensaje = '<p style="color:red;">ID de artículo no especificado.</p>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Artículo</title>
</head>
<body>
    <h1><?php echo ($articulo) ? 'Editar Artículo: ' . htmlspecialchars($articulo['nombre']) : 'Artículo No Encontrado'; ?></h1>
    <?php echo $mensaje; ?>

    <?php if ($articulo): ?>
        <form method="POST" action="/LRVS/Tienda_zapatillas/editar_articulo.php"> 
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($articulo['id_articulo']); ?>">

            <label>Nombre:</label><input type="text" name="nombre" value="<?php echo htmlspecialchars($articulo['nombre']); ?>" required><br>
            <label>Descripción:</label><textarea name="descripcion"><?php echo htmlspecialchars($articulo['descripcion']); ?></textarea><br>
            <label>Precio:</label><input type="number" name="precio" step="0.01" value="<?php echo htmlspecialchars($articulo['precio']); ?>" required><br>
            <label>Stock:</label><input type="number" name="stock" value="<?php echo htmlspecialchars($articulo['stock']); ?>" required><br>
            <label>Imagen URL:</label><input type="text" name="imagen" value="<?php echo htmlspecialchars($articulo['imagen']); ?>" required><br>

            <label>Género:</label>
            <select name="genero" required>
                <option value="Hombre" <?php echo ($articulo['genero'] == 'Hombre') ? 'selected' : ''; ?>>Hombre</option>
                <option value="Mujer" <?php echo ($articulo['genero'] == 'Mujer') ? 'selected' : ''; ?>>Mujer</option>
                <option value="Unisex" <?php echo ($articulo['genero'] == 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
            </select><br>

            <label>Categoría:</label>
            <select name="categoria" required>
                <option value="Running" <?php echo ($articulo['categoria'] == 'Running') ? 'selected' : ''; ?>>Running</option>
                <option value="Casual" <?php echo ($articulo['categoria'] == 'Casual') ? 'selected' : ''; ?>>Casual</option>
                <option value="Baloncesto" <?php echo ($articulo['categoria'] == 'Baloncesto') ? 'selected' : ''; ?>>Baloncesto</option>
            </select><br>

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php endif; ?>
    
    <p><a href="/LRVS/Tienda_zapatillas/gestion_articulos.php">Volver a Gestión de Artículos</a></p>
</body>
</html>