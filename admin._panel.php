<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Aura Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Panel de Administración</h1>
    <nav>
        <ul>
            <li><a href="index.html">Inicio</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="carrito.html">Carrito</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>

<section id="admin-panel">
    <h2>Agregar Nuevo Producto</h2>
    <form action="procesar_agregar_producto.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre del producto:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="categoria">Categoría ID:</label>
        <input type="number" id="categoria" name="categoria" required>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>

        <label for="talla">Talla:</label>
        <input type="text" id="talla" name="talla">

        <button type="submit">Agregar Producto</button>
    </form>
</section>

<footer>
    <p>&copy; 2025 Aura Store - Todos los derechos reservados</p>
</footer>

</body>
</html>
