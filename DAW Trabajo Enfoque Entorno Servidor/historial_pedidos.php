<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Redirección de seguridad 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?msg=' . urlencode('Inicia sesión para ver tus pedidos.'));
    exit;
}

// 3. Carga de servicios y datos
require_once 'servicios/historial_service.php';
$id_usuario = $_SESSION['user_id'];
$historial = getHistorialCompleto($id_usuario);

// 4. Configuración de página y carga de cabecera
$page_title = 'Mi Historial de Pedidos | CROSS-KICKS';
require_once 'header.php'; 
?>

<div class="container py-5 mt-4">
    <h1 class="mb-5 text-white fw-bold">
        <i class="fas fa-box-open me-2 text-primary"></i>Mis Pedidos
    </h1>

    <?php if (empty($historial)): ?>
        <div class="glass-panel p-5 text-center border-info">
            <i class="fas fa-shopping-cart mb-3 text-muted" style="font-size: 3rem;"></i>
            <p class="fs-4 text-muted">Aún no has realizado ningún pedido.</p>
            <a href="catalogo.php" class="btn btn-primary btn-lg mt-3" style="background-color: #9f59f9; border: none;">
                ¡Explorar Catálogo!
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($historial as $pedido): ?>
                <div class="col-12 mb-4">
                    <div class="card shadow-lg" style="background: rgba(20, 20, 20, 0.8); border: 1px solid var(--ck-primary); border-radius: 15px; overflow: hidden;">
                        
                        <div class="card-header bg-dark d-flex justify-content-between align-items-center border-bottom border-secondary py-3">
                            <div>
                                <span class="text-primary fw-bold fs-5">PEDIDO #<?php echo htmlspecialchars($pedido['id_pedido']); ?></span>
                                <span class="text-muted ms-3 small"><i class="far fa-calendar-alt me-1"></i><?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?></span>
                            </div>
                            <span class="badge fs-5" style="background-color: #9f59f9;">
                                <?php echo number_format($pedido['total'], 2); ?> €
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="text-info text-uppercase fw-bold mb-3 small" style="letter-spacing: 1px;">Artículos en el envío:</h6>
                            <div class="list-group list-group-flush">
                                <?php foreach ($pedido['detalles'] as $detalle): ?>
                                    <div class="list-group-item bg-transparent text-light border-secondary px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($detalle['cantidad']); ?>x</span>
                                                <span class="fw-bold"><?php echo htmlspecialchars($detalle['nombre']); ?></span>
                                                <small class="text-warning ms-2">(Talla <?php echo htmlspecialchars($detalle['talla'] ?? 'N/A'); ?>)</small>
                                            </div>
                                            <span class="text-muted"><?php echo number_format($detalle['precio_unitario'] * $detalle['cantidad'], 2); ?> €</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-dark border-top border-secondary d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="text-muted small me-2">Estado:</span>
                                <span class="badge bg-success py-2 px-3">
                                    <i class="fas fa-check-circle me-1"></i><?php echo htmlspecialchars($pedido['estado_pedido'] ?? 'Completado'); ?>
                                </span>
                            </div>
                            <button class="btn btn-outline-info btn-sm rounded-pill px-3">
                                <i class="fas fa-file-invoice me-1"></i>Detalles
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
require_once 'footer.php'; 
?>