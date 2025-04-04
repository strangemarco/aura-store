
<?php
$dsn = "mysql:host=localhost;dbname=aurastore;charset=utf8mb4";
$user = "root";
$pass = ""; // sin contraseña si usas root local y autenticación de Windows

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
