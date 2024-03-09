<?php
require_once "./config.php";
require_once "Usuarios.php";
require_once "Seguridad.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}
$mensaje = ''; 
$usuarios = new usuarios($conexion);
$datosUsuario = $usuarios->consultarUsuario($_GET['login']);

$seguridad = new Seguridad(); // Crear una instancia de la clase Seguridad

if (!$seguridad->tienePermiso('admin')) {
    header("Location: ./index.php"); // Redirigir si no tiene permiso de administrador
    exit(); // Finalizar la ejecución del script
}

if (isset($_POST['Actualizar'])) {
    $salt = random_int(0, 1000000000);
    $password = $salt . $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $datosUsuarioActualizados = array(
        'nombre' => $_POST['nombre'],
        'apellidos' => $_POST['apellidos'],
        'login' => $_POST['login'],
        'password' => $passwordHash,
        'rol' => $_POST['rol'],
        'salt' => $salt
    );

    $mensaje = "Usuario no actualizado"; // Mensaje predeterminado

    // Verifica si los datos del usuario están presentes y se han actualizado correctamente
    if ($usuarios->actualizarUsuario($datosUsuarioActualizados)) {
        $mensaje = "Usuario actualizado correctamente";
        $datosUsuario = $usuarios->consultarUsuario($_GET['login']);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Simple Dashboard</title>
    <link rel="stylesheet" href="./estilos/style.css">
</head>

<body>
<?php
$rolUsuario = $seguridad->obtenerRol();
    if ($rolUsuario === 'admin') {
        require_once "./cabecera.php";
        echo $cabecera;
    } elseif ($rolUsuario === 'bibliotecario') {
        require_once "./cabeceraBibliotecario.php";
        echo $cabeceraBibliotecario;
    } else {
        require_once "./cabeceraUser.php";
        echo $cabeceraUser;
    }
    ?>
    <form method="POST" action="">
        <fieldset>
            <legend>Actualizar Usuario</legend>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $datosUsuario['nombre'] ?>" required><br>
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="<?php echo $datosUsuario['apellidos'] ?>" required><br>
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?php echo $datosUsuario['login'] ?>" required><br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="<?php echo $datosUsuario['password'] ?>" required><br>
            <label for="rol">Rol</label>
            <select name="rol" id="rol">
                <option value="admin" <?php if ($datosUsuario['rol'] == 'admin') echo 'selected' ?>>Admin</option>
                <option value="bibliotecario" <?php if ($datosUsuario['rol'] == 'bibliotecario') echo 'selected' ?>>Bibliotecario</option>
                <option value="user" <?php if ($datosUsuario['rol'] == 'user') echo 'selected' ?>>User</option>
            </select><br>
            <input type="submit" name="Actualizar" value="Actualizar">
        </fieldset>
    </form>
        <?php
        if (isset($mensaje)) {
            echo "<p>$mensaje</p>";
        }
        ?>

</body>

</html>
<?php
$conexion->close();
?>