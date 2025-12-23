<?php
session_start();
require_once 'servicios/user_service.php';

// 1. Seguridad y Control de Acceso
$current_rol = isset($_SESSION['user_rol']) ? strtolower($_SESSION['user_rol']) : '';
if ($current_rol !== 'admin') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$usuario = ($id) ? obtenerUsuarioPorId($id) : null; 

if (!$usuario) {
    header('Location: gestion_usuarios.php?msg=' . urlencode('Usuario no encontrado'));
    exit;
}

// 2. Lógica de Actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    if (actualizarDatosUsuario($id, $nombre, $email, $rol)) {
        header('Location: gestion_usuarios.php?msg=' . urlencode('Usuario actualizado correctamente'));
        exit;
    } else {
        $error = "Error crítico: No se pudieron guardar los cambios.";
    }
}

$page_title = "Editar Perfil de Agente | CROSS-KICKS";
require_once 'header.php'; // Aquí ya se cargan los estilos base y la base_url
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="checkout-container glass-panel p-4 shadow-lg" 
                 style="border: 2px solid #9f59f9; border-radius: 20px; background: rgba(20, 20, 20, 0.9); color: white;">
                
                <div class="text-center mb-4">
                    <i class="fas fa-user-edit text-info" style="font-size: 3rem; filter: drop-shadow(0 0 10px #0dcaf0);"></i>
                    <h2 class="mt-3 fw-bold">EDITAR AGENTE</h2>
                    <p class="text-muted small">ID de Usuario: #<?php echo $id; ?></p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger bg-danger text-white border-0"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-info">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control bg-dark text-white border-secondary" 
                               value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-info">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" 
                               value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-info">Rango en el Sistema</label>
                        <select name="rol" class="form-select bg-dark text-white border-secondary">
                            <option value="cliente" <?php echo ($usuario['rol'] === 'cliente') ? 'selected' : ''; ?>>CLIENTE (User)</option>
                            <option value="admin" <?php echo ($usuario['rol'] === 'admin') ? 'selected' : ''; ?>>ADMINISTRADOR (Overlord)</option>
                        </select>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold" 
                                style="background: linear-gradient(45deg, #9f59f9, #0dcaf0); border: none;">
                            <i class="fas fa-save me-2"></i>GUARDAR CAMBIOS
                        </button>
                        <a href="gestion_usuarios.php" class="btn btn-outline-light">
                            <i class="fas fa-times me-2"></i>CANCELAR
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>