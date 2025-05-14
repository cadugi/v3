<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "tienda");
if (!$conexion) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen'];

    $permitidas = ['png', 'jpg', 'jpeg', 'webp'];
    $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $permitidas)) {
        die("Formato de imagen no permitido.");
    }

    if (!is_dir('images')) {
        mkdir('images');
    }

    $ruta_imagen = 'images/' . basename($imagen['name']);
    if (!move_uploaded_file($imagen['tmp_name'], $ruta_imagen)) {
        die("Error al subir la imagen.");
    }

    // Obtener id del usuario
    $email = $_SESSION['usuario'];
    $res = mysqli_query($conexion, "SELECT id_usuario FROM usuarios WHERE email = '$email'");
    $usuario = mysqli_fetch_assoc($res);
    if (!$usuario) {
        die("Usuario no encontrado.");
    }
    $id_vendedor = $usuario['id_usuario'];

    // Insertar producto
    $stmt = mysqli_prepare($conexion, "INSERT INTO productos (nombre, categoria, descripcion, precio, imagen, id_vendedor) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $categoria, $descripcion, $precio, $ruta_imagen, $id_vendedor);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: productos.php");
        exit();
    } else {
        echo "Error al insertar: " . mysqli_error($conexion);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar Producto</title>
    <link rel="stylesheet" href="style-publicar.css">
</head>
<body>
    <h1>Nuevo Producto</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <select name="categoria" required>
            
            <option value="">Selecciona una categoría</option>
            <?php
            $res = mysqli_query($conexion, "SELECT nombre_categoria FROM categorias");
            while ($cat = mysqli_fetch_assoc($res)) {
                echo "<option value='" . htmlspecialchars($cat['nombre_categoria']) . "'>" . htmlspecialchars($cat['nombre_categoria']) . "</option>";
            }
            ?>
        </select>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <input type="number" name="precio" placeholder="Precio" required>
        <input type="file" name="imagen" accept=".png,.jpg,.jpeg,.webp" required>
        <button type="submit">Publicar</button>
    </form>
</body>
</html>
