<?php
require 'db_connection.php';

try {
    $sql = "SELECT * FROM PRODUCTOS";
    $stmt = $conn->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Aura Store</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .producto-card select {
            margin-top: 5px;
            padding: 5px;
            width: 100%;
        }
        .precio-original {
            text-decoration: line-through;
            color: gray;
            font-size: 0.9em;
        }
        .precio-descuento {
            color: #e91e63;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>Aura Store</h1>
    <nav>
        <ul>
            <li><a href="index.html">Ofertas</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="carrito.html">Carrito</a></li>
            <li><a href="login.html">Iniciar Sesión</a></li>
            <li><a href="contacto.html">Contacto</a></li>
            <li><a href="gestion_entregas.php">Entregas</a></li>
        </ul>
    </nav>
</header>

<section class="productos">
    <h2>Lista de Productos</h2>
    <div class="productos-grid">
        <?php foreach ($productos as $producto): 
            $precioOriginal = $producto['PRECIO'];
            $precioDescuento = $precioOriginal * 0.80;
        ?>
            <div class="producto-card">
                <img src="<?= $producto['IMAGEN'] ?>" alt="<?= $producto['NOMBRE'] ?>" class="producto-img">
                <h3><?= $producto['NOMBRE'] ?></h3>
                <p><?= $producto['DESCRIPCION'] ?></p>
                <p>
                    <span class="precio-original">$<?= number_format($precioOriginal, 2) ?></span><br>
                    <span class="precio-descuento">$<?= number_format($precioDescuento, 2) ?> (20% OFF)</span>
                </p>
                <p class="stock">Stock: <?= $producto['STOCK'] ?></p>

                <?php if (!empty($producto['TALLA'])): ?>
                <label for="talla-<?= $producto['PRODUCTOID'] ?>">Talla:</label>
                <select id="talla-<?= $producto['PRODUCTOID'] ?>">
                    <?php foreach (explode(',', $producto['TALLA']) as $talla): ?>
                        <option value="<?= trim($talla) ?>"><?= trim($talla) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <?php if ($producto['STOCK'] > 0): ?>
                <button class="agregar-carrito"
                    data-id="<?= $producto['PRODUCTOID'] ?>"
                    data-nombre="<?= htmlspecialchars($producto['NOMBRE']) ?>"
                    data-precio="<?= $precioDescuento ?>"
                    data-imagen="<?= $producto['IMAGEN'] ?>"
                    data-talla-id="talla-<?= $producto['PRODUCTOID'] ?>">
                    Agregar al carrito
                </button>
                <?php else: ?>
                    <p style="color: red; font-weight: bold;">Agotado</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<footer>
    <p>&copy; 2025 Aura Store - Todos los derechos reservados</p>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("agregar-carrito")) {
                const btn = e.target;
                const tallaSelect = document.getElementById(btn.dataset.tallaId);
                const talla = tallaSelect ? tallaSelect.value : "";

                const producto = {
                    id: btn.dataset.id,
                    nombre: btn.dataset.nombre,
                    precio: parseFloat(btn.dataset.precio),
                    imagen: btn.dataset.imagen,
                    talla: talla,
                    cantidad: 1
                };

                const existente = carrito.find(p => p.id === producto.id && p.talla === producto.talla);
                if (existente) {
                    existente.cantidad++;
                } else {
                    carrito.push(producto);
                }

                localStorage.setItem("carrito", JSON.stringify(carrito));
                alert(`\"${producto.nombre}\" fue agregado al carrito (Talla: ${producto.talla})`);
            }
        });
    });
</script>

</body>
</html>
