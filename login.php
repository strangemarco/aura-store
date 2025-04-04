<?php
require 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    

    try {
        $sql = "SELECT USUARIOID, CONTRASEÑA FROM USUARIOS WHERE CORREO = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["CONTRASEÑA"])) {
            $_SESSION["user_id"] = $user["USUARIOID"];
            $clienteID = $user["USUARIOID"];
            header("Location: login_success.html?cliente_id=$clienteID");
            exit();
            
        } else {
            echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='login.html';</script>";
        }
    } catch (PDOException $e) {
        echo "Error en el login: " . $e->getMessage();
    }
    

    header("Location: login_success.html?cliente_id=$clienteID&rol={$user['ROL']}");

}
?>
