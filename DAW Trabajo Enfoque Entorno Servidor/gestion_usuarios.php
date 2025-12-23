<?php
session_start();
require_once 'servicios/user_service.php';

// 1. Control de acceso: Solo administradores
$user_rol = isset($_SESSION['user_rol']) ? strtolower($_SESSION['user_rol']) : '';

if ($user_rol !== 'admin') {
    header('Location: index.php'); // Si no es admin, te echa.
    exit;
}

$usuarios = listarUsuarios();

// 2. Gestión de mensajes de feedback
$mensaje = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);

$page_title = "Gestión de Usuarios | Administrador";
require_once 'header.php'; 
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Gestión de Usuarios</h1>
        <a href="registro.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Nuevo Usuario
        </a>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow border-primary bg-dark text-white">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-dark align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td>#<?php echo $u['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo $u['rol'] === 'admin' ? 'bg-danger' : 'bg-info'; ?>">
                                        <?php echo strtoupper($u['rol']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar_usuario.php?id=<?php echo $u['id_usuario']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($u['id_usuario'] != $_SESSION['user_id']): ?>
                                    <a href="eliminar_usuario.php?id=<?php echo $u['id_usuario']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron usuarios.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>