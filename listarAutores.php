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
$autores = new Autores($conexion);


if (isset($_GET['borrar'])) {

    $consulta = "DELETE FROM `autores` WHERE `idAutor` = '$_GET[borrar]'";
    $conexion->query($consulta);
    $conexion->close();
    header("Location:./listarAutores.php");
    $mensaje = "Autor borrado correctamente";
    echo $mensaje;
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
    <link type="text/css" rel="stylesheet" href="./estilos/style.css">
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

    <main class="autoresMain">
        <h2>Listado de autores</h2>
        <table border="1px">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Nacionalidad</th>
            </tr>

            <?php
            require_once "Autores.php";
            $autores = new Autores($conexion);
            $todosLosAutores = $autores->consultarAutor();

            if ($todosLosAutores) {
                foreach ($todosLosAutores as $autor) {
                    echo "<tr>";
                    echo "<td>" . $autor['idAutor'] . "</td>";
                    echo "<td>" . $autor['nombre'] . "</td>";
                    echo "<td>" . $autor['apellidos'] . "</td>";
                    echo "<td>" . $autor['pais'] . "</td>";
                    echo "<td style='background: linear-gradient(90deg, rgba(83,91,255,1) 21%, rgba(48,133,252,1) 48%, rgba(15,197,252,1) 100%);'><a href='?borrar={$autor['idAutor']}'>Borrar</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No se encontraron autores.</td></tr>";
            }
            ?>

        </table>
    </main>
</body>

</html>