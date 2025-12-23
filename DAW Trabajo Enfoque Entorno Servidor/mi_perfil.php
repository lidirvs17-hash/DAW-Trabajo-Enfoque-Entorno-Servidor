<?php
session_start();

require_once 'servicios/user_service.php';

// Solo si está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode('mi_perfil.php'));
    exit;
}

require_once 'header.php'; 

$page_title = 'Mi Perfil';

// 1. Obtener Datos del Usuario desde la Sesión

$user_details = [
    'nombre' => $_SESSION['user_nombre'] ?? 'Usuario Desconocido',
    // Si no tienes el email en sesión, actualiza tu login_service.php para incluirlo.
    'email' => $_SESSION['user_email'] ?? 'correo@ejemplo.com', 
];



$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['nombre'])) {
        $message = '<div class="alert alert-success">¡Datos personales actualizados! (Lógica DB pendiente)</div>';
    } elseif (isset($_POST['current_password'])) {
        $message = '<div class="alert alert-success">¡Contraseña cambiada! (Lógica DB pendiente)</div>';
    }
}

?>

<h1 class="mb-4">Configuración de Perfil</h1>
<?= $message ?>

<div class="row">
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow border-primary">
            <div class="card-header bg-dark text-warning">
                <i class="fas fa-id-card me-2"></i> Actualizar Datos Personales
            </div>
            <div class="card-body bg-dark text-light">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($user_details['nombre']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user_details['email']) ?>" required>
                        <div class="form-text text-muted">Tu correo actual.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow border-secondary">
            <div class="card-header bg-dark text-danger">
                <i class="fas fa-lock me-2"></i> Cambiar Contraseña
            </div>
            <div class="card-body bg-dark text-light">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync me-1"></i> Actualizar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<?php 
require_once 'footer.php'; 
?>