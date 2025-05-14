<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    die("Error: El usuario no ha iniciado sesión.");
}

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "tienda");

if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Obtener el nombre del producto a comprar
if (!isset($_GET['id_producto'])) {
    die("<p>Error: No se ha seleccionado ningún producto.</p>");
}

$id_producto = intval($_GET['id_producto']);

// Obtener el nombre del producto desde la base de datos
$sql_producto = "SELECT nombre FROM productos WHERE id_producto = ?";
$stmt_producto = mysqli_prepare($conexion, $sql_producto);
mysqli_stmt_bind_param($stmt_producto, "i", $id_producto);
mysqli_stmt_execute($stmt_producto);
$resultado_producto = mysqli_stmt_get_result($stmt_producto);
$producto = mysqli_fetch_assoc($resultado_producto);
mysqli_stmt_close($stmt_producto);

if (!$producto) {
    die("<p>Error: No se encontró el producto en la base de datos.</p>");
}

$nombre_producto = $producto['nombre'];

// Establecer la zona horaria de Madrid
date_default_timezone_set('Europe/Madrid'); 
$fecha_compra = date('Y-m-d H:i:s');

// Registrar la compra en la tabla `datos`
$sql_compra = "INSERT INTO datos (nombre_producto, fecha_compra) VALUES (?, ?)";
$stmt_compra = mysqli_prepare($conexion, $sql_compra);
mysqli_stmt_bind_param($stmt_compra, "ss", $nombre_producto, $fecha_compra);

if (mysqli_stmt_execute($stmt_compra)) {

    // Eliminar el producto de la base de datos
    $sql_eliminar = "DELETE FROM productos WHERE id_producto = ?";
    $stmt_eliminar = mysqli_prepare($conexion, $sql_eliminar);
    mysqli_stmt_bind_param($stmt_eliminar, "i", $id_producto);

    if (mysqli_stmt_execute($stmt_eliminar)) {
        $mensaje = "¡Compra realizada con éxito!";
    } else {
        $mensaje = "Error al eliminar el producto.";
    }

    mysqli_stmt_close($stmt_eliminar);
} else {
    $mensaje = "Error al registrar la compra.";
}

mysqli_stmt_close($stmt_compra);
mysqli_close($conexion);

// Mostrar el mensaje de resultado
echo "<p>$mensaje</p>";
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra</title>
    <link rel="stylesheet" href="style-comprar.css">
</head>
<body>
    <h1><?php echo $mensaje; ?></h1>
    <a href="index.php"><button>Volver al inicio</button></a>
</body>
</html>
