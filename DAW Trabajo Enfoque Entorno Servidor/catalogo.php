<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'servicios/articulo_service.php';
require_once 'servicios/cart_service.php';

initCart();

// Obtenemos los productos
$articulos = obtenerCatalogo(); 

$mensaje = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
$page_title = 'Catálogo | CROSS-KICKS';
require_once 'header.php'; 
?>

<div class="container py-4">
    <div class="glass-panel mb-5 shadow-sm text-center">
        <h1 class="display-5 fw-bold neon-title">EQUIPAMIENTO DISPONIBLE</h1>
        <p class="text-muted">Selecciona tu loot y prepárate para la misión.</p>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-info alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i> <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold" style="color: var(--ck-dark-green);">Resultados (<?php echo count($articulos); ?>)</h2>
    </div>

    <?php if (count($articulos) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($articulos as $articulo): ?>
                <div class="col">
                    <div class="card h-100 card-custom shadow-sm border-0">
                        <div class="p-3 text-center bg-white" style="border-radius: 15px 15px 0 0;">
                             <img src="assets/img/<?php echo htmlspecialchars($articulo['imagen']); ?>" 
                                  class="card-img-top img-fluid" 
                                  alt="<?php echo htmlspecialchars($articulo['nombre']); ?>" 
                                  style="max-height: 200px; object-fit: contain;">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($articulo['nombre']); ?></h5>
                            <div class="mb-3">
                                <span class="fs-4 fw-bold text-primary"><?php echo number_format($articulo['precio'], 2); ?> €</span>
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                <?php echo htmlspecialchars($articulo['descripcion'] ?? 'Sin descripción detallada.'); ?>
                            </p>

                            <hr class="text-light-grey">

                            <?php 
                                $total_stock_articulo = 0; 
                                foreach ($articulo['tallas'] as $v) { $total_stock_articulo += $v['stock']; }
                            ?>

                            <?php if ($total_stock_articulo > 0): ?>
                                <form action="add_to_cart.php" method="GET" class="row g-2 align-items-end">
                                    <input type="hidden" name="id" value="<?php echo $articulo['id_articulo']; ?>">
                                    
                                    <div class="col-6">
                                        <label class="small fw-bold text-muted">Talla</label>
                                        <select name="talla" class="form-select form-select-sm" required onchange="actualizarMaximo(this)">
                                            <option value="">--</option>
                                            <?php foreach ($articulo['tallas'] as $variante): ?>
                                                <?php if ($variante['stock'] > 0): ?>
                                                    <option value="<?php echo htmlspecialchars($variante['talla']); ?>" 
                                                            data-stock="<?php echo $variante['stock']; ?>">
                                                        <?php echo htmlspecialchars($variante['talla']); ?> (<?php echo $variante['stock']; ?>)
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="small fw-bold text-muted">Cant.</label>
                                        <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm input-cantidad">
                                    </div>
                                    
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary w-100 shadow-sm fw-bold">
                                            <i class="fas fa-cart-plus me-2"></i> AÑADIR AL INVENTARIO
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 disabled fw-bold">
                                    <i class="fas fa-times-circle me-2"></i> AGOTADO
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="glass-panel text-center py-5">
            <p class="fs-4 text-muted">No hay artículos disponibles en el catálogo.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function actualizarMaximo(selectElement) {
    // Buscamos el formulario contenedor
    const form = selectElement.closest('form');
    const inputCantidad = form.querySelector('.input-cantidad');
    
    // Obtenemos el stock de la opción seleccionada
    const option = selectElement.options[selectElement.selectedIndex];
    const stockDisponible = option.getAttribute('data-stock');
    
    if (stockDisponible) {
        inputCantidad.max = stockDisponible;
        // Si el valor actual supera el nuevo máximo, lo ajustamos
        if (parseInt(inputCantidad.value) > parseInt(stockDisponible)) {
            inputCantidad.value = stockDisponible;
        }
    }
}
</script>

<?php require_once 'footer.php'; ?>