<?php
// Activa errores para ver qué pasa si falla
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'servicios/user_service.php';

$current_rol = isset($_SESSION['user_rol']) ? strtolower($_SESSION['user_rol']) : '';

if ($current_rol !== 'admin') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id && $id != $_SESSION['user_id']) {
    // Si borrarUsuario devuelve false (por la FK de pedidos), irá al else
    if (borrarUsuario($id)) {
        $msg = "Usuario eliminado correctamente.";
    } else {
        $msg = "Error: No se puede eliminar. El usuario tiene pedidos vinculados.";
    }
} else {
    $msg = "Acción no permitida o ID inválido.";
}

header('Location: gestion_usuarios.php?msg=' . urlencode($msg));
exit; 
?>