<?php
require 'db_connection.php';


$data = json_decode(file_get_contents("php://input"), true);

// Debug temporal: muestra los datos recibidos
file_put_contents('debug.txt', print_r($data, true));



header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Leer JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset(
    $data['carrito'],
    $data['clienteID'],
    $data['metodoPago'],
    $data['nombre'],
    $data['correo'], // <-- Asegúrate de incluirlo aquí
    $data['carnet'],
    $data['telefono'],
    $data['direccion']
)) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos necesarios."]);
    exit;
}

$correo = $data['correo']; // <-- Y que lo estés capturando


$correo = $data['correo']; // ✨ NUEVO


$carrito = $data['carrito'];
$clienteID = intval($data['clienteID']);
$metodoPago = $data['metodoPago'];
$nombre = $data['nombre'];
$carnet = $data['carnet'];
$telefono = $data['telefono'];
$direccion = $data['direccion'];
$total = 0;

foreach ($carrito as $item) {
    $total += floatval($item['precio']) * intval($item['cantidad']);
}

try {
    $conn->beginTransaction();

    // Insertar en pedidos
    $stmtPedido = $conn->prepare("INSERT INTO pedidos (CLIENTEID) VALUES (?)");
    $stmtPedido->execute([$clienteID]);
    $pedidoID = $conn->lastInsertId();

    // Insertar en ventas
    $stmtVenta = $conn->prepare("INSERT INTO ventas (CLIENTEID, TOTAL) VALUES (?, ?)");
    $stmtVenta->execute([$clienteID, $total]);
    $ventaID = $conn->lastInsertId();

    // Insertar en detalleventas y actualizar stock
    $stmtDetalle = $conn->prepare("INSERT INTO detalleventas (VENTAID, PRODUCTOID, CANTIDAD, PRECIOUNITARIO) VALUES (?, ?, ?, ?)");
    $stmtStock = $conn->prepare("UPDATE productos SET STOCK = STOCK - ? WHERE PRODUCTOID = ?");

    foreach ($carrito as $item) {
        $productoID = intval($item['id']);
        $cantidad = intval($item['cantidad']);
        $precio = floatval($item['precio']);

        // Insertar en detalleventas
        $stmtDetalle->execute([$ventaID, $productoID, $cantidad, $precio]);

        // Actualizar stock
        $stmtStock->execute([$cantidad, $productoID]);
    }

    // Insertar en pagos
    $stmtPago = $conn->prepare("INSERT INTO pagos (PEDIDOID, METODOPAGO, MONTOPAGADO) VALUES (?, ?, ?)");
    $stmtPago->execute([$pedidoID, $metodoPago, $total]);

    // Insertar en entrega
    $stmtEntrega = $conn->prepare("INSERT INTO entrega (PEDIDOID, DIRECCIONENVIO, NOMBRE, CORREO, CARNET, TELEFONO) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtEntrega->execute([$pedidoID, $direccion, $nombre, $correo, $carnet, $telefono]);
    
    $conn->commit();
    echo json_encode(["success" => true, "mensaje" => "Compra registrada exitosamente"]);
} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(["error" => "Error al registrar la compra", "detalle" => $e->getMessage()]);
}
