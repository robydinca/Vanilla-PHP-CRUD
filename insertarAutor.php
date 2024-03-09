<?php
require_once "config.php";
require_once "Autores.php";
require_once "Seguridad.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

$seguridad = new Seguridad();
if (!$seguridad->tienePermiso('admin') && !$seguridad->tienePermiso('bibliotecario')) {
    header("Location: ./index.php");
    exit();
}

$mensaje = ''; 
$autores = new autores($conexion);

if (isset($_POST['insertar'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['apellidos']) && !empty($_POST['pais'])) {
        $datosAutor = array(
            'nombre' => $_POST['nombre'],
            'apellidos' => $_POST['apellidos'],
            'pais' => $_POST['pais']
        );

        $autores->insertar($datosAutor);
        $mensaje = "Autor insertado correctamente";
    } else {
        $mensaje = "Por favor, complete todos los campos del formulario.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Simple Dashboard</title>
    <link type='text/css' rel='stylesheet' href='./estilos/style.css'>
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
    <main>
        <h2>Insertar Autor</h2>
        <form method="POST" action="">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required>
            <br>
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" required>
            <br>
            <label for="pais">Nacionalidad</label>
            <input type="text" name="pais" id="pais" required>
            <br>
            <input type="submit" value="Insertar" name="insertar" class="boton">
        </form>

        <?php
        echo $mensaje;
        ?>
    </main>
</body>

</html>