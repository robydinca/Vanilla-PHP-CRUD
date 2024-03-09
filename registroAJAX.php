<?php
require_once "config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
  die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $login = $_POST["login"];

  // Comprobar si el login ya existe en la base de datos
  $consultaExistencia = "SELECT login FROM usuarios WHERE login = '$login'";
  $resultadoExistencia = $conexion->query($consultaExistencia);

  if ($resultadoExistencia->num_rows > 0) {
    echo "El nombre de usuario ya está registrado. Por favor, elige otro.";
    exit;
  }

  $salt = random_int(0, 1000000000);
  $nombre = $_POST["nombre"];
  $apellidos = $_POST["apellidos"];
  $password = $salt . $_POST["password"];
  $rol = $_POST["rol"];

  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  $consulta = "INSERT INTO usuarios VALUES ('$nombre', '$apellidos', '$salt', '$login', '$passwordHash', '$rol')";
  if ($conexion->query($consulta) === TRUE) {
    echo "Usuario creado correctamente";
  } else {
    echo "Error al crear el usuario: " . $conexion->error;
  }

  $conexion->close();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario</title>
  <link type="text/css" rel="stylesheet" href="./estilos/forms.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    $(document).ready(function () {
      $("#login").blur(function () {
        var login = $(this).val();
        $.ajax({
          type: "POST",
          url: "registroAJAX.php", 
          data: { login: login },
          success: function (response) {
            $("#mensaje").html(response);
          }
        });
      });
    });
  </script>
</head>

<body>
  <form action="" method="post">
    <h1>Registro de Usuario</h1>
    <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
    <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
    <input type="text" name="login" id="login" placeholder="Usuario" required>
    <div id="mensaje"></div>
    <input type="password" name="password" id="password" placeholder="Contraseña" required>
    <select id="rol" name="rol">
      <option value="user">Usuario</option>
      <option value="admin">Administrador</option>
      <option value="bibliotecario">Bibliotecario</option>
    </select>
    <input type="submit" value="Registrarse" class="boton">
  </form>
</body>

</html>
