<?php
require_once 'db_connection.php';
/*
  Función para obtener un usuario por su email
 @param string $email Correo electrónico del usuario
 @return array|false
    - Array con los datos del usuario si se encuentra
    - false si no se encuentra o hay un error
*/
function getUserByEmail($email) {
    $conn = connectDB();
    if (!$conn) {
        return false; // Manejo de error si la conexión falla
    }
    // Consulta preparada para evitar inyecciones SQL
$sql = "SELECT id_usuario, email, password, rol, nombre, apellido FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
         // Manejo de error si la preparación de la consulta falla
         error_log("Error al preparar la consulta: " . $conn->error); 
         $conn->close();
         return false;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        
        $user = $result->fetch_assoc(); 
    } else { 
        
        $user = false; // Usuario no encontrado
    }
   
    $stmt->close();
    $conn->close();
    return $user;
}
  /*  
  @param string $email
  @param string $hashedPassword Contraseña hasheada.
  @param string $nombre
  @param string $apellido
  @param string $rol Rol del usuario ('Cliente' o 'Admin').
  @return bool true si la inserción fue exitosa, false en caso contrario.
  */  
  function insertUser($email, $hashedPassword, $nombre, $apellido, $rol) {
    $conn = connectDB();
    if (!$conn) {
        return false; 
    }
    // Consulta preparada para evitar inyecciones SQL
    $sql = "INSERT INTO usuarios (email, password, rol, nombre, apellido) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
         // Manejo de error si la preparación de la consulta falla
         error_log("Error al preparar la consulta: " . $conn->error); 
         $conn->close();
         return false;
    }
    $stmt->bind_param("sssss", $email, $hashedPassword, $rol, $nombre, $apellido);
    $success = $stmt->execute();

    if (!$success) {
        error_log("Error al insertar el usuario: " . $stmt->error);
    }
   
    $stmt->close();
    $conn->close();
    
    return $success;
  }
//Listado de todos los usuarios
function getAllUsers() {
    $conn = connectDB();
    $sql = "SELECT id_usuario, nombre, email, rol FROM usuarios";
    $result = $conn->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) { $users[] = $row; }
    $conn->close();
    return $users;
}
//Borrar usuarios
function deleteUser($id) {
    // Activa que mysqli lance excepciones para poder atraparlas
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = connectDB();
        if (!$conn) return false;

        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        return $success;

    } catch (Exception $e) { 
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        // Registramos el error en el log del servidor
        error_log("Error al borrar usuario: " . $e->getMessage());
        return false; 
    }
}

// Para obtener los datos actuales del usuario y modificarlo en el formulario
function getUserById($id) {
    $conn = connectDB();
    $sql = "SELECT id_usuario, nombre, email, rol FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}

// Para guardar los nuevos datos
function updateUser($id, $nombre, $email, $rol) {
    $conn = connectDB();
    $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $email, $rol, $id);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}
?>