<?php
if (isset($_POST["host"]) && isset($_POST["usuario"]) && isset($_POST["password"]) && isset($_POST["nombreBaseDatos"])) {
    $host = $_POST["host"];
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $nombreBaseDatos = $_POST["nombreBaseDatos"];
    $puerto = $_POST["puerto"];
    //encripta la contraseña ademas de añadir un salt
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    
    $configContenido = '<?php' . "\n";
    $configContenido .= 'define("HOST", "' . $host . '");' . "\n";
    $configContenido .= 'define("USER", "' . $usuario . '");' . "\n";
    $configContenido .= 'define("PASSWORD", "' . $password . '");' . "\n";
    $configContenido .= 'define("DB", "' . $nombreBaseDatos . '");' . "\n";
    $configContenido .= 'define("PORT", "' . $puerto . '");' . "\n";
    $configContenido .= '?' . '>' . "\n";

    $archivo = fopen("config.php", "w");
    fwrite($archivo, $configContenido);
    fclose($archivo);
  

    //creacion de tablas
    $conexion = new mysqli($host, $usuario, $password, $nombreBaseDatos, $puerto);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $consultas[] = 
      "CREATE TABLE IF NOT EXISTS usuarios (
      nombre VARCHAR(50) NOT NULL,
      apellidos VARCHAR(50) NOT NULL,
      salt VARCHAR(20) NOT NULL,
      login VARCHAR(50) NOT NULL PRIMARY KEY,
      password VARCHAR(512) NOT NULL,
      rol enum('admin', 'bibliotecario', 'user') NOT NULL)";

    $consultas[] = 
      "CREATE TABLE IF NOT EXISTS autores (
      idAutor INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      nombre VARCHAR(50) NOT NULL,
      apellidos VARCHAR(50) NOT NULL,
      pais VARCHAR(50) NOT NULL)";

    $consultas[] = 
      "CREATE TABLE IF NOT EXISTS libros (
      idLibro INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      titulo VARCHAR(50) NOT NULL,
      genero enum('Narrativa', 'Lirica', 'Teatro', 'Cientifico-Tecnico') NOT NULL,
      idAutor INT UNSIGNED NOT NULL,
      numeroPaginas INT UNSIGNED NOT NULL,
      numeroEjemplares INT UNSIGNED NOT NULL,
      FOREIGN KEY (idAutor) REFERENCES autores(idAutor))";


    foreach ($consultas as $consulta) {
      if ($conexion->query($consulta) === TRUE) {
        header("Location: registroAdmin.php");
      } else {
        echo "Error al crear la tabla: " . $conexion->error . "<br>";
      }
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
  <link rel="stylesheet" href="./estilos/forms.css">
</head>

<body class="install" style="display:flex; flex-direction:column; padding: 40px;">
  
<form action="" method="post" class="installForm">
  <h1>Instalación de la Aplicación Biblioteca</h1>
  <input type="text" name="host" placeholder="Host" required>
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="text" name="nombreBaseDatos" placeholder="Nombre Base de Datos" required>
    <input type="text" name="puerto" placeholder="Puerto" required>
    <input type="submit" value="Aceptar" class="boton">
  </form>

</body>

</html>
