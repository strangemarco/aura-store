<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"]; 
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encriptación segura

    try {
        $sql = "INSERT INTO USUARIOS (NOMBRE, APELLIDO, CORREO, TELEFONO, CONTRASEÑA) 
                VALUES (:nombre, :apellido, :email, :telefono, :password)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellido", $apellido);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":password", $password);
        
        $stmt->execute();

        // ✅ Obtener el ID del nuevo usuario
        $usuarioID = $conn->lastInsertId();

        // ✅ Redirigir a login_success.html con el ID
        header("Location: login_success.html?cliente_id=$usuarioID");
        exit();
        
    } catch (PDOException $e) {
        echo "Error al registrar: " . $e->getMessage();
    }
}
?>
