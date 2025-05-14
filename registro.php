<?php
session_start(); // Iniciar sesión

// Conexión clásica (tema 5)
$conexion = mysqli_connect("localhost", "root", "", "tienda");

if (!$conexion) {
    echo "Error al conectar: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    exit();
}

// Verificamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtenemos los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $repetir_contraseña = $_POST['repetir_contraseña'];
    $telefono = $_POST['telefono'];

    // Verificamos que las contraseñas coincidan
    if ($contraseña != $repetir_contraseña) {
        $_SESSION['registro_error'] = "Las contraseñas no coinciden.";
        header("Location: registro.php");
        exit();
    }

    // Verificamos si el email o teléfono ya existen
    $sql = "SELECT * FROM Usuarios WHERE email = '$email' OR telefono = '$telefono'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $_SESSION['registro_error'] = "El correo o el teléfono ya están registrados.";
        header("Location: registro.php");
        exit();
    }

    // Insertamos el nuevo usuario (sin usar UUID ni hash)
    $sql_insertar = "INSERT INTO Usuarios (nombre, apellidos, email, contraseña, telefono)
                     VALUES ('$nombre', '$apellidos', '$email', '$contraseña', '$telefono')";

    $ok = mysqli_query($conexion, $sql_insertar);

    if ($ok) {
        $_SESSION['usuario'] = $email;
        $_SESSION['nombre_completo'] = $nombre . " " . $apellidos;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['registro_error'] = "Error al registrar el usuario.";
        header("Location: registro.php");
        exit();
    }

    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="style-registro.css">
</head>
<body class="registro-body">
    <h1 class="registro-titulo">Registro de Usuario</h1>

    <?php
    if (isset($_SESSION['registro_error'])) {
        echo "<p style='color: red; text-align: center;'>" . $_SESSION['registro_error'] . "</p>";
        unset($_SESSION['registro_error']);
    }
    ?>

    <form action="registro.php" method="POST" class="registro-form">
        <div class="input-group">
            <label class="registro-label">Nombre:</label>
            <input type="text" name="nombre" class="registro-input" required>
        </div>

        <div class="input-group">
            <label class="registro-label">Apellidos:</label>
            <input type="text" name="apellidos" class="registro-input" required>
        </div>

        <div class="input-group">
            <label class="registro-label">Email:</label>
            <input type="email" name="email" class="registro-input" required>
        </div>

        <div class="input-group">
            <label class="registro-label">Contraseña:</label>
            <input type="password" name="contraseña" class="registro-input" required>
        </div>

        <div class="input-group">
            <label class="registro-label">Repetir Contraseña:</label>
            <input type="password" name="repetir_contraseña" class="registro-input" required>
        </div>

        <div class="input-group">
            <label class="registro-label">Teléfono:</label>
            <input type="text" name="telefono" class="registro-input" required>
        </div>

        <div class="botones-contenedor">
            <button type="submit" class="registro-boton">Registrarse</button>
            <button type="button" class="login-boton" onclick="window.location.href='login.php'">Ir a Login</button>
        </div>
    </form>
</body>
</html>
