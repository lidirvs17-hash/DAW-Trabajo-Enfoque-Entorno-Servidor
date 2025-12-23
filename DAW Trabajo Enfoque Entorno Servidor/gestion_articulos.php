<?php
session_start();
require_once __DIR__ . '/servicios/articulo_service.php';

require_once 'header.php'; 

// Lista de tallas estándar (usada para dibujar el formulario)
$tallas_estandar = ['37', '38', '39', '40', '41', '42', '43', '44', '45']; 

// CONTROL DE ACCESO: Solo usuarios ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'Admin') {
    header('Location: index.php');
    exit;
}

$mensaje = '';
$articulo_a_editar = null; // Almacena el artículo si estamos en modo edición
$stock_map = []; // Almacena el stock actual mapeado [talla => stock]
$form_action = 'crear'; // Por defecto: crear
$page_title = 'Gestión de Artículos';

// =========================================================
// LÓGICA DE MANEJO DE PETICIONES (GET para Editar, POST para Guardar)
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'editar') {
    // A. Carga del Formulario de Edición (GET)
    $id_articulo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id_articulo) {
        $articulo_a_editar = getArticuloById($id_articulo); // Del DAO
        if ($articulo_a_editar) {
            // CRUCIAL: Obtener el stock actual mapeado por talla (del DAO)
            $stock_map = getStockMapByArticuloId($id_articulo); 
            $form_action = 'editar';
            $page_title = 'Editar Artículo';
        } else {
            $mensaje = '<div class="alert alert-danger">Artículo no encontrado.</div>';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // B. Lógica de Guardado (POST) - Aplica a CREATE y EDITAR
    $accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_articulo = filter_input(INPUT_POST, 'id_articulo', FILTER_VALIDATE_INT);
    
    // Recoger y sanitizar datos comunes
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $imagen = filter_input(INPUT_POST, 'imagen', FILTER_SANITIZE_URL);
    $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_SPECIAL_CHARS);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS); 
    
    // Recoger y procesar las tallas y stock
    $tallas_input = $_POST['tallas'] ?? [];
    $variantes_final = [];
    foreach ($tallas_input as $talla => $stock) {
        $stock_sanitizado = filter_var($stock, FILTER_VALIDATE_INT);
        if (!empty($talla) && $stock_sanitizado !== false) {
            $variantes_final[$talla] = $stock_sanitizado; // [talla => stock]
        }
    }

    $exito_articulo = false;
    
    // --- LÓGICA CREATE / UPDATE ---
    if ($accion === 'crear') {
        if (empty($variantes_final)) {
            $mensaje = '<div class="alert alert-danger">Error: Debe introducir al menos una talla.</div>';
        } else {
            // Crear Artículo principal y obtener el ID
            $new_id = insertArticulo($nombre, $descripcion, $precio, $imagen, $genero, $categoria);
            if ($new_id) {
                $id_articulo = $new_id;
                $exito_articulo = true;
            }
        }
    } elseif ($accion === 'editar' && $id_articulo) {
        // Actualizar Artículo principal (sin stock)
        $exito_articulo = updateArticulo($id_articulo, $nombre, $descripcion, $precio, $imagen, $genero, $categoria);
    }

    // --- LÓGICA DE STOCK (Común a CREATE y UPDATE) ---
    $exito_stock = true;
    if ($exito_articulo && !empty($variantes_final)) {
        foreach ($variantes_final as $talla => $stock) {
            // updateArticuloTallaStock inserta o actualiza el stock de la variante
            if (!updateArticuloTallaStock($id_articulo, $talla, $stock)) { 
                $exito_stock = false;
                error_log("Fallo al actualizar stock para talla: $talla");
            }
        }
    }

    // --- MENSAJE FINAL y REDIRECCIÓN ---
    if ($exito_articulo && $exito_stock) {
        $mensaje = '<div class="alert alert-success">Artículo ' . (($accion === 'crear') ? 'creado' : 'actualizado') . ' con éxito.</div>';
    } else {
        $mensaje = '<div class="alert alert-danger">Error al procesar el artículo. Revise el stock.</div>';
    }
    
    //  precargamos el formulario de edición de nuevo
    if ($accion === 'editar' && $id_articulo) {
        $articulo_a_editar = getArticuloById($id_articulo); 
        $stock_map = getStockMapByArticuloId($id_articulo); 
        $form_action = 'editar';
    }
    
    // Redirección para limpiar el POST y mostrar el mensaje
    header('Location: gestion_articulos.php?msg=' . urlencode(strip_tags($mensaje)));
    exit;
} 

// Carga de la lista de artículos para la tabla (sin filtros en Admin)
$articulos = obtenerCatalogo(); 
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0 text-warning">
                    <i class="fas fa-tools me-2"></i> 
                    <?php echo ($form_action === 'editar') ? 'EDITAR ARTÍCULO' : 'GESTIÓN DE INVENTARIO'; ?>
                </h3>
                <?php if ($form_action === 'editar'): ?>
                    <a href="gestion_articulos.php" class="btn btn-sm btn-outline-warning">Cancelar Edición</a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php echo $mensaje; ?>

                <form method="POST" action="gestion_articulos.php">
                    <input type="hidden" name="accion" value="<?php echo $form_action; ?>">
                    <?php if ($form_action === 'editar'): ?>
                        <input type="hidden" name="id_articulo" value="<?php echo htmlspecialchars($articulo_a_editar['id_articulo']); ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($articulo_a_editar['nombre'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio (€):</label>
                            <input type="number" name="precio" step="0.01" class="form-control" value="<?php echo htmlspecialchars($articulo_a_editar['precio'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea name="descripcion" class="form-control" rows="2"><?php echo htmlspecialchars($articulo_a_editar['descripcion'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label for="imagen" class="form-label">URL Imagen:</label>
                            <input type="text" name="imagen" class="form-control" value="<?php echo htmlspecialchars($articulo_a_editar['imagen'] ?? ''); ?>" required>
                        </div>

                        <div class="col-12">
                            <h5 class="mt-3 text-warning border-bottom pb-2">Inventario y Tallas</h5>
                            <div class="row">
                                <?php foreach ($tallas_estandar as $talla): ?>
                                    <div class="col-md-2 col-sm-4 mb-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">T. <?php echo $talla; ?></span>
                                            <input type="number" 
                                                   name="tallas[<?php echo $talla; ?>]" 
                                                   class="form-control" 
                                                   min="0" 
                                                   value="<?php echo htmlspecialchars($stock_map[$talla] ?? 0); ?>" 
                                                   placeholder="Stock">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="genero" class="form-label">Género:</label>
                            <select name="genero" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <?php $selected_genero = $articulo_a_editar['genero'] ?? ''; ?>
                                <option value="Hombre" <?php echo ($selected_genero === 'Hombre') ? 'selected' : ''; ?>>Hombre</option>
                                <option value="Mujer" <?php echo ($selected_genero === 'Mujer') ? 'selected' : ''; ?>>Mujer</option>
                                <option value="Unisex" <?php echo ($selected_genero === 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="categoria" class="form-label">Categoría:</label>
                            <select name="categoria" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <?php $selected_categoria = $articulo_a_editar['categoria'] ?? ''; ?>
                                <option value="Running" <?php echo ($selected_categoria === 'Running') ? 'selected' : ''; ?>>Running</option>
                                <option value="Boxeo" <?php echo ($selected_categoria === 'Boxeo') ? 'selected' : ''; ?>>Boxeo</option>
                                <option value="Senderismo/Escalada" <?php echo ($selected_categoria === 'Senderismo/Escalada') ? 'selected' : ''; ?>>Senderismo/Escalada</option>
                                <option value="Entrenamiento" <?php echo ($selected_categoria === 'Entrenamiento') ? 'selected' : ''; ?>>Entrenamiento</option>
                                <option value="Baloncesto" <?php echo ($selected_categoria === 'Baloncesto') ? 'selected' : ''; ?>>Baloncesto</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">
                        <i class="fas fa-save me-2"></i> <?php echo ($form_action === 'editar') ? 'Guardar Cambios' : 'Crear Artículo'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<h2 class="mt-5 text-warning border-bottom pb-2">Artículos Existentes</h2>

<div class="table-responsive">
    <table class="table table-dark table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Tallas / Stock</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($articulos as $articulo): ?>
            <tr>
                <td><?php echo htmlspecialchars($articulo['id_articulo']); ?></td>
                <td><?php echo htmlspecialchars($articulo['nombre']); ?></td>
                <td><?php echo number_format($articulo['precio'], 2) . ' €'; ?></td>
                <td>
                    <?php 
                        $tallas_info = [];
                        foreach ($articulo['tallas'] as $variante) {
                            $tallas_info[] = htmlspecialchars($variante['talla']) . ' (' . htmlspecialchars($variante['stock']) . ')';
                        }
                        echo implode(', ', $tallas_info);
                    ?>
                </td>
                <td><?php echo htmlspecialchars($articulo['categoria']); ?></td>
                <td>
                    <a href="gestion_articulos.php?accion=editar&id=<?php echo $articulo['id_articulo']; ?>" class="btn btn-sm btn-outline-info">Editar</a>
                    <a href="eliminar_articulo.php?id=<?php echo $articulo['id_articulo']; ?>" 
                       onclick="return confirm('ATENCIÓN: Se eliminará el artículo y todas sus tallas. ¿Confirmar?');" 
                       class="btn btn-sm btn-outline-danger">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
require_once 'footer.php'; 
?>