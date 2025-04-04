<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entregaID = $_POST['entrega_id'] ?? null;
    $nuevoEstado = $_POST['nuevo_estado'] ?? null;

    if ($entregaID && $nuevoEstado) {
        $stmt = $conn->prepare("UPDATE entrega SET ESTADOENTREGA = ? WHERE ENTREGAID = ?");
        $stmt->execute([$nuevoEstado, $entregaID]);

        header("Location: gestion_entregas.php");
        exit;
    } else {
        echo "Faltan datos para actualizar el estado.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
