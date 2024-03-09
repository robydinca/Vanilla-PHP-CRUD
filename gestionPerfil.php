<?php
require_once "./Seguridad.php";
require_once "./Usuarios.php";
require_once "./config.php";

$seguridad = new Seguridad();
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!$seguridad->estaAutenticado()) {
    header('Location: login.php');
    exit;
}

$mensaje = '';
$datosUsuario = [];

$usuarios = new Usuarios($conexion);
$datosUsuario = $usuarios->consultarUsuario($_SESSION['login']);
$saltUser = $datosUsuario['salt'];

if (isset($_POST['contrasena_actual'])) {
  $contrasena_actual = $saltUser . $_POST['contrasena_actual'];

  // Verificar si la contraseña actual coincide con la almacenada
  if (password_verify($contrasena_actual, $datosUsuario['password'])) {
      // Si la contraseña es correcta, mostrar el formulario de actualización
      $mostrarFormulario = true;

      // Almacenar la verificación de la contraseña actual en una variable de sesión para recordarla
      $_SESSION['verificacion_contrasena'] = true;
  } else {
      $mensaje = "La contraseña actual es incorrecta. Por favor, inténtelo de nuevo.";
  }
}

if (isset($_POST['Actualizar']) && isset($_SESSION['verificacion_contrasena']) && $_SESSION['verificacion_contrasena']) {
    // Procesar la actualización si la contraseña fue verificada correctamente
    $salt = random_int(0, 1000000000);
    $password = $salt . $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $datosUsuarioActualizados = array(
        'nombre' => $_POST['nombre'],
        'apellidos' => $_POST['apellidos'],
        'login' => $_POST['login'],
        'password' => $passwordHash,
        'salt' => $salt
    );

    $mensaje = "Perfil no actualizado"; // Mensaje predeterminado

    // Verificar si los datos del usuario se actualizaron correctamente
    if ($usuarios->actualizarUsuario($datosUsuarioActualizados)) {
        $mensaje = "Perfil actualizado correctamente";
        $datosUsuario = $usuarios->consultarUsuario($_SESSION['login']);
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<?php if (!isset($mostrarFormulario) || (isset($mostrarFormulario) && !$mostrarFormulario)) : ?>
    <!-- Formulario para ingresar la contraseña actual -->
    <form method="POST" action="">
        <fieldset>
            <legend>Confirmar Contraseña</legend>
            <label for="contrasena_actual">Contraseña Actual</label>
            <input type="password" name="contrasena_actual" id="contrasena_actual" required>
            <input type="submit" value="Confirmar">
        </fieldset>
    </form>
    <?php echo $mensaje; ?>
<?php else : ?>
    <!-- Formulario de actualización -->
    <form method="POST" action="">
        <fieldset>
            <legend>Actualizar Perfil</legend>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $datosUsuario['nombre'] ?>" required><br>
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="<?php echo $datosUsuario['apellidos'] ?>" required><br>
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?php echo $datosUsuario['login'] ?>" required readonly><br>
            <label for="password">Nueva Contraseña</label>
            <input type="password" name="password" id="password" required><br>
            <input type="submit" name="Actualizar" value="Actualizar">
            <input type="reset" value="Limpiar">
        </fieldset>
    </form>
    <?php echo $mensaje; ?>
<?php endif; ?>
</body>
</html>
