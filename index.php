<?php
session_start();

// Redirigir a login si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener rol para decidir comportamiento
$rol = $_SESSION['rol'] ?? 'anonimo';

// Imagen de perfil
$imagen_perfil = 'images/default-profile.png';
if ($rol == 'anonimo') {
    $imagen_perfil = 'images/usuarios-prep/anonimo.png';
} elseif ($rol == 'usuario') {
    $imagen_perfil = 'images/usuarios-prep/0.png';
} elseif ($rol == 'admin') {
    $imagen_perfil = 'images/usuarios-prep/admin.png';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Fonsi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<!-- Parte superior con imagen y logout -->
<div style="position: absolute; top: 10px; right: 10px;">
    <a href="logout.php"><img src="images/apagado.png" width="30" title="Cerrar sesión"></a>
    <img src="<?php echo $imagen_perfil; ?>" width="50" style="border-radius: 50%;">
</div>

<main>
    <h1>Bienvenido, <?php echo $_SESSION['nombre_completo']; ?>!</h1>

    <p>En nuestra tienda encontrarás muchos productos de calidad. Queremos darte siempre el mejor precio.</p>

    <?php if ($rol == 'admin') : ?>
        <a href="subir_categoria.php"><button>Subir nueva categoría</button></a>
    <?php endif; ?>

    <h2>Algunos productos destacados:</h2>
    <ul>
        <li><a href="productos.php#motor">Motor y accesorios</a><img src="images/motor.png" alt=""></li>
        <li><a href="productos.php#moda">Moda y accesorios</a><img src="images/ropa-limpia.png" alt=""></li>
        <li><a href="productos.php#electrodomesticos">Electrodomésticos</a><img src="images/lavadora.png" alt=""></li>
        <li><a href="productos.php#moviles">Móviles y telefonía</a><img src="images/telefono.png" alt=""></li>
        <li><a href="productos.php#informatica">Informática y electrónica</a><img src="images/computadora.png" alt=""></li>
        <li><a href="productos.php#deportes">Deporte y ocio</a><img src="images/deporte.png" alt=""></li>
        <li><a href="productos.php#tv">TV, audio y fotografía</a><img src="images/camara.png" alt=""></li>
        <li><a href="productos.php#jardin">Hogar y Jardín</a><img src="images/flores.png" alt=""></li>
        <li><a href="productos.php#libros">Cine, libros y música</a><img src="images/libro.png" alt=""></li>
        <li><a href="productos.php#niño">Niños y bebés<img src="images/chico.png" alt=""></a></li>
    </ul>
</main>

<footer>
    <p>&copy; 2025 Tienda General Fonsi. Proyecto de práctica.</p>
</footer>

</body>
</html>
