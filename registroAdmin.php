<?php
require_once "config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salt = random_int ( 0 , 1000000000 );
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $login = $_POST["login"];
    $password = $salt . $_POST["password"];
    $rol = "admin";

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $consulta = "INSERT INTO usuarios VALUES ('$nombre', '$apellidos', '$salt', '$login', '$passwordHash', '$rol')";
    if ($conexion->query($consulta) === TRUE) {
        echo "Usuario creado correctamente<br>";
    } else {
        echo "Error al crear el usuario: " . $conexion->error . "<br>";
    }

    $conexion->close();

    header("Location: index.php");
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
    <h1>Registrate</h1>
      <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
      <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
      <input type="text" name="login" id="login" placeholder="Usuario" required>
      <input type="password" name="password" id="password" placeholder="Contraseña" required>
      <input type="submit" value="Enviar" class="boton">
</body>

</html>