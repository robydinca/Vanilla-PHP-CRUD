<?php
require_once "config.php";
require_once "Usuarios.php";
require_once "Seguridad.php"; // Incluye la clase Seguridad

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

$seguridad = new Seguridad(); // Instancia la clase Seguridad

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $consulta = "SELECT * FROM usuarios WHERE login = '$login'";
    $resultado = $conexion->query($consulta);
    $usuario = $resultado->fetch_assoc();

    if (password_verify($usuario['salt'] . $password, $usuario['password'])) {
        $seguridad->login($login, $usuario['rol']); // Utiliza el método login de la clase Seguridad
        header("Location: index.php");
        exit;
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Dashboard</title>
  <link type="text/css" rel="stylesheet" href="./estilos/forms.css">
</head>

<body>
  <form action="" method="post">
    <h1>Login</h1>
    <input type="text" name="login" id="login" placeholder="Usuario" required>
    <input type="password" name="password" id="password" placeholder="Contraseña" required>
    <input type="submit" value="Enviar" class="boton">
  </form>
</body>

</html>
