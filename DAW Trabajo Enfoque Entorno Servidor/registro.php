<?php

session_start();
require_once 'servicios/user_service.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    
    
    if (strlen($password) < 6) {
        $mensaje = '<div class="alert alert-danger" role="alert">La contraseña debe tener al menos 6 caracteres.</div>';
    } else if (registerUser($email, $password, $nombre, $apellido)) {
        header('Location: login.php?registro=exito');
        exit;
    } else {
        $mensaje = '<div class="alert alert-danger" role="alert">Error al intentar registrar. El email podría estar ya en uso.</div>';
    }
}

$page_title = 'Registro de Usuario';
require_once 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h3 class="card-title mb-0">Crear Nueva Cuenta</h3>
            </div>
            <div class="card-body">
                <?php echo $mensaje; ?>
                
                <form method="POST" action="registro.php">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registrarme</button>
                </form>
            </div>
            <div class="card-footer text-center">
                ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>