<?php
session_start();
require_once 'servicios/articulo_service.php';
require_once 'servicios/cart_service.php';

initCart();


$articulos = obtenerCatalogo(); 

$mensaje = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
$page_title = 'CROSS-KICKS';
require_once 'header.php'; 
?>

<h1 class="mb-4 text-center">Catálogo de Productos</h1>
    
<?php if ($mensaje): ?>
    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($mensaje); ?></div>
<?php endif; ?>

<h2 class="mb-4">Resultados (<?php echo count($articulos); ?>)</h2>

<?php if (count($articulos) > 0): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($articulos as $articulo): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                   <img src="<?php echo $base_url; ?>assets/img/<?php echo htmlspecialchars($articulo['imagen']); ?>" class="card-img-top p-2" alt="<?php echo htmlspecialchars($articulo['nombre']); ?>" style="height: 250px; object-fit: contain;">
                    
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($articulo['nombre']); ?></h5>
                        <p class="card-text fw-bold text-primary fs-5"><?php echo number_format($articulo['precio'], 2); ?> €</p>
                            <p class="card-text text-muted small mb-2" style="min-height: 40px;">
                                <?php echo htmlspecialchars($articulo['descripcion'] ?? 'Sin descripción detallada.'); ?>
                            </p>
                        <p class="text-muted small">Disponibles: 
                            <?php 
                                $tallas_info = [];
                                $total_stock_articulo = 0; 
                                foreach ($articulo['tallas'] as $variante) {
                                    $tallas_info[] = $variante['talla'] . ' (' . $variante['stock'] . ')';
                                    $total_stock_articulo += $variante['stock']; 
                                }
                                echo implode(', ', $tallas_info);
                            ?>
                        </p>

                        <?php if ($total_stock_articulo > 0): ?>
                            <form action="add_to_cart.php" method="GET" class="mt-auto d-flex flex-wrap gap-2 justify-content-center">
                                <input type="hidden" name="id" value="<?php echo $articulo['id_articulo']; ?>">
                                
                                <select name="talla" class="form-select form-select-sm" required style="width: 90px;" 
                                 onchange="actualizarMaximo(this)">
                                 <option value="" data-stock="1">Talla</option>
                                    <?php foreach ($articulo['tallas'] as $variante): ?>
                                        <?php if ($variante['stock'] > 0): ?>
                                            <option value="<?php echo htmlspecialchars($variante['talla']); ?>" 
                                            data-stock="<?php echo $variante['stock']; ?>">
                                            <?php echo htmlspecialchars($variante['talla']); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                
                                <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm input-cantidad" style="width: 60px;">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="fas fa-shopping-cart me-1"></i> Añadir
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="text-danger fw-bold mt-auto">❌ AGOTADO</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-warning" role="alert">
        No hay artículos disponibles en el catálogo en este momento.
    </div>
<?php endif; ?>

<script>
function actualizarMaximo(selectElement) {
    // Buscamos el input de cantidad que está al lado del select
    const inputCantidad = selectElement.parentElement.querySelector('.input-cantidad');
    // Obtenemos el stock de la opción seleccionada
    const stockDisponible = selectElement.options[selectElement.selectedIndex].getAttribute('data-stock');
    
    if (stockDisponible) {
        inputCantidad.max = stockDisponible;
        if (parseInt(inputCantidad.value) > parseInt(stockDisponible)) {
            inputCantidad.value = stockDisponible;
        }
    }
}
</script>

<?php 
require_once 'footer.php'; 
?>