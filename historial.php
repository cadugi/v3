<?php
$conexion = mysqli_connect("localhost", "root", "", "tienda");
$res = mysqli_query($conexion, "SELECT * FROM datos ORDER BY fecha_compra DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial</title>
    <link rel="stylesheet" href="style-historial.css">
</head>
<body>
<h2>Historial de compras</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Fecha</th>
    </tr>
    <?php
    if (mysqli_num_rows($res) > 0) {
        while ($fila = mysqli_fetch_assoc($res)) {
            echo "<tr>";
            echo "<td>{$fila['id_compra']}</td>";
            echo "<td>" . htmlspecialchars($fila['nombre_producto']) . "</td>";
            echo "<td>{$fila['fecha_compra']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Sin compras registradas</td></tr>";
    }
    mysqli_close($conexion);
    ?>
</table>

<button><a href="index.php">Volver</a></button>
</body>
</html>
