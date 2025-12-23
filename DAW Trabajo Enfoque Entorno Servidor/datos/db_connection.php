<?php
// Detectar si el servidor es local o remoto
$is_localhost = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1');

if ($is_localhost) {
    // Configuración XAMPP (local)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tienda_zapatillas'); 
} else {
    // Configuración para InfinityFree (Remoto)
    define('DB_HOST', 'sql110.infinityfree.com');
    define('DB_USER', 'if0_40734835');
    define('DB_PASS', 'cGYy8FrC5f'); 
    define('DB_NAME', 'if0_40734835_cross_kicks');
}

if (!function_exists('connectDB')) {
    function connectDB() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
    }
}


$conn = connectDB();
?>
