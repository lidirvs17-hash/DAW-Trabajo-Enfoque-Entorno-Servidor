<?php
session_start();
require_once 'servicios/user_service.php';

$mensaje = '';

// Si viene de registro 
if (isset($_GET['registro']) && $_GET['registro'] === 'exito') {
    $mensaje = '<div class="alert alert-success" role="alert">¡Registro exitoso! Ya puedes iniciar sesión.</div>';
}

// Si viene de logout
if (isset($_GET['logout'])) {
    $mensaje = '<div class="alert alert-info" role="alert">Has cerrado sesión correctamente.</div>';
}

// Si viene de redirección forzada y tiene mensaje
$msg_url = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
if ($msg_url) {
    $mensaje = '<div class="alert alert-warning" role="alert">' . htmlspecialchars($msg_url) . '</div>';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    
    $usuario = loginUser($email, $password);

    if ($usuario) {
        $_SESSION['user_id'] = $usuario['id_usuario'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_rol'] = $usuario['rol'];
        $_SESSION['user_nombre'] = $usuario['nombre'];
        
        // Redirigir a la página anterior o al índice
        $redirect_to = filter_input(INPUT_GET, 'redirect', FILTER_SANITIZE_URL);
        
        if ($redirect_to) {
            header('Location: ' . $redirect_to);
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $mensaje = '<div class="alert alert-danger" role="alert">Credenciales incorrectas. Verifique email y contraseña.</div>';
    }
}

$page_title = 'Iniciar Sesión';
require_once 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title mb-0">Acceso a la Tienda</h3>
            </div>
            <div class="card-body">
                <?php echo $mensaje; ?>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Iniciar Sesión</button>
                </form>
            </div>
            <div class="card-footer text-center">
                ¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>