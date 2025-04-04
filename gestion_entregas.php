<?php
require 'db_connection.php';

$query = $conn->query("
    SELECT 
        e.ENTREGAID, 
        e.DIRECCIONENVIO, 
        e.ESTADOENTREGA, 
        e.FECHAENTREGA, 
        e.NOMBRE, 
        e.CARNET, 
        e.TELEFONO, 
        e.CORREO,  -- AÑADIDO
        p.FECHAPEDIDO
    FROM entrega e
    JOIN pedidos p ON e.PEDIDOID = p.PEDIDOID
    ORDER BY e.ENTREGAID DESC
");

$entregas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Entregas - Aura Store</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f6f6f6;
            padding: 40px;
        }

        h2 {
            color: #e91e63;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #e91e63;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background: #f1f1f1;
        }

        select, button {
            padding: 6px 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .volver {
            margin-top: 30px;
            text-align: center;
        }

        .volver a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #e91e63;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .volver a:hover {
            background-color: #c2185b;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                border-radius: 5px;
                overflow: hidden;
                background: #fff;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                top: 12px;
                font-weight: bold;
                text-align: left;
                color: #555;
            }
        }
    </style>
</head>
<body>

    <h2>Gestión de Entregas</h2>

    <table>
    <thead>
    <tr>
        <th>ID Entrega</th>
        <th>Cliente</th>
        <th>Correo</th> <!-- NUEVO -->
        <th>Carnet</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Estado</th>
        <th>Fecha del Pedido</th>
        <th>Cambiar Estado</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($entregas as $entrega): ?>
        <tr>
            <td data-label="ID Entrega"><?= $entrega['ENTREGAID'] ?></td>
            <td data-label="Cliente"><?= htmlspecialchars($entrega['NOMBRE']) ?></td>
            <td data-label="Correo"><?= htmlspecialchars($entrega['CORREO']) ?></td> <!-- NUEVO -->
            <td data-label="Carnet"><?= htmlspecialchars($entrega['CARNET']) ?></td>
            <td data-label="Dirección"><?= htmlspecialchars($entrega['DIRECCIONENVIO']) ?></td>
            <td data-label="Teléfono"><?= htmlspecialchars($entrega['TELEFONO']) ?></td>
            <td data-label="Estado"><?= htmlspecialchars($entrega['ESTADOENTREGA']) ?></td>
            <td data-label="Fecha"><?= $entrega['FECHAPEDIDO'] ?></td>
            <td data-label="Cambiar Estado">
                <form method="POST" action="actualizar_estado_entrega.php">
                    <input type="hidden" name="entrega_id" value="<?= $entrega['ENTREGAID'] ?>">
                    <select name="nuevo_estado" required>
                        <option value="En preparación" <?= $entrega['ESTADOENTREGA'] === 'En preparación' ? 'selected' : '' ?>>En preparación</option>
                        <option value="En camino" <?= $entrega['ESTADOENTREGA'] === 'En camino' ? 'selected' : '' ?>>En camino</option>
                        <option value="Entregado" <?= $entrega['ESTADOENTREGA'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                    </select>
                    <button type="submit">Actualizar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>

    <div class="volver">
        <a href="index.html">← Volver a la página principal</a>
    </div>

</body>
</html>
