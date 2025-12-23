<?php
$page_title = 'Inicio | CROSS-KICKS'; 
require_once 'header.php'; 

$user_name = $_SESSION['user_nombre'] ?? 'Invitado';
$user_rol = $_SESSION['user_rol'] ?? 'Cliente';
?>

<div class="row">
    <div class="col-12">
        <div class="alert shadow-sm" role="alert" style="background-color: var(--ck-primary); color: white; border-left: 5px solid var(--ck-accent);">
            <h4 class="alert-heading fw-bold">Bienvenido a Cross-Kicks, <?php echo htmlspecialchars($user_name); ?>!</h4>
            <p>Equípate, Forja tu Leyenda y Conquista. Tu aventura comienza aquí.</p>
            <hr style="background-color: var(--ck-soft-red); opacity: 0.3;">
            <p class="mb-0">Tu nivel de acceso actual es: <span class="badge" style="background-color: var(--ck-dark-red);"><?php echo htmlspecialchars($user_rol); ?></span></p>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header text-white fw-bold">
                <i class="fas fa-shoe-prints me-2"></i>CATÁLOGO
            </div>
            <div class="card-body d-flex flex-column" style="background-color: var(--ck-card-bg);">
                <h5 class="card-title fw-bold" style="color: var(--ck-dark-red);">Tus zapas para seguir subiendo de nivel .</h5>
                <p class="card-text">Ediciones Limitadas que no verás en el Loot de nadie más.</p>
                <a href="catalogo.php" class="btn btn-primary mt-auto">VER CATÁLOGO</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header text-white fw-bold">
                <i class="fas fa-history me-2"></i>PEDIDOS
            </div>
            <div class="card-body d-flex flex-column" style="background-color: var(--ck-card-bg);">
                <h5 class="card-title fw-bold" style="color: var(--ck-dark-red);">Historial: Recarga y Reconfigura</h5>
                <p class="card-text">Consulta tu historial de misiones pasadas. ¿Quieres volver a equipar tu par favorito?</p>
                <a href="historial_pedidos.php" class="btn btn-primary mt-auto">VER HISTORIAL</a>
            </div>
        </div>
    </div>
</div>

<?php 
require_once 'footer.php'; 
?>
