<?php
require_once __DIR__ . '/../datos/user_dao.php';
/**
 * Función para autenticar al usuario.
 * @param string $email Correo electrónico del usuario.
 * @param string $password_plana Contraseña en texto plano.
 * @return array|false Devuelve el array del usuario (sin password) si es exitoso, o false.
 */
function loginUser($email, $password_plana) {
    
    $user = getUserByEmail($email);

    if (!$user) {
    
        error_log("Intento de login fallido: email no encontrado: " . $email);
        return false; 
    }

    $hash_almacenado = $user['password'];
    
    if (password_verify($password_plana, $hash_almacenado)) {
     
        
        unset($user['password']); // Eliminar la contraseña del array por seguridad
        
        return $user;
        
    } else {
    
        error_log("Intento de login fallido: contraseña incorrecta para el email: " . $email);
        return false; 
    }
}
/** Función para registrar un nuevo usuario
 *@param string $email Correo electrónico del usuario
 *@param string $password_plana Contraseña en texto plano proporcionada por el usuario
 *@param string $nombre Nombre del usuario
 *@param string $apellido Apellido del usuario
 *@return bool true si el registro es exitoso, false si hay un error.
 */
    function registerUser($email, $password_plana, $nombre, $apellido) {
        if (empty($email) || empty($password_plana) || empty($nombre) || empty($apellido)) {
            return false; 
        }
         if (!preg_match('/^(?=.*\d).{8,}$/', $password_plana)) {
        
        error_log("Intento de registro fallido: Contraseña no cumple requisitos de seguridad (email: " . $email . ")");
        return false; 
    }
        $hashedPassword = password_hash($password_plana, PASSWORD_DEFAULT);
        $rol = 'Cliente';
        $success = insertUser($email, $hashedPassword, $nombre, $apellido, $rol);
        if (!$success) {
            error_log("Error al registrar el usuario con email: " . $email);
        }
        return $success;

    }  
function listarUsuarios() { 
    return getAllUsers(); 
}

function borrarUsuario($id) {
    return deleteUser($id); 
}

function obtenerUsuarioPorId($id) {
    return getUserById($id);
}

function actualizarDatosUsuario($id, $nombre, $email, $rol) {
    
    return updateUser($id, $nombre, $email, $rol);
}
?>