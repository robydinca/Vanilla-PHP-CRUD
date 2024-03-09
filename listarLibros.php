<?php
require_once "config.php";
require_once "Libros.php";
require_once "Autores.php";
require_once "Seguridad.php";

$mensaje = "";
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT) or die("Error de conexión: " . $conexion->connect_error);

$seguridad = new Seguridad();
if (!$seguridad->tienePermiso('admin') && !$seguridad->tienePermiso('bibliotecario')) {
    header("Location: ./index.php");
    exit();
}


$libros = new Libros($conexion);
$todosLosLibros = $libros->consultarLibro();


if (isset($_GET['borrar'])) {
    $consulta = "DELETE FROM `libros` WHERE `idLibro` = '$_GET[borrar]'";
    $conexion->query($consulta);
    $conexion->close();
    header("Location:./listarLibros.php");
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

    <main>
        <h2>Listado de libros</h2>
        <table border="2px">
            <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Género</th>
                <th>Nombre autor</th>
                <th>Número de páginas</th>
                <th>Número de ejemplares</th>
                <th>Actualizar</th>
                <th>Eliminar</th>

                <?php
                require_once "Libros.php";
                $libros = new Libros($conexion);
                $listaLibros = $libros->consultarLibro();
                $autores = new Autores($conexion);

                foreach ($listaLibros as $libro) {
                    echo "<tr>";
                    echo "<td>" . $libro['idLibro'] . "</td>";
                    echo "<td>" . $libro['titulo'] . "</td>";
                    echo "<td>" . $libro['genero'] . "</td>";

                    $autor = $autores->consultarAutor($libro['idAutor']);
                    if (!empty($autor) && isset($autor[0]['nombre']) && isset($autor[0]['apellidos'])) {
                        echo "<td>" . $autor[0]['nombre'] . " " . $autor[0]['apellidos'] . "</td>";
                    } else {
                        echo "<td>No se encontró el autor</td>";
                    }

                    echo "<td>" . $libro['numeroPaginas'] . "</td>";
                    echo "<td>" . $libro['numeroEjemplares'] . "</td>";
                    echo "<td style='background: linear-gradient(90deg, rgba(83,91,255,1) 21%, rgba(48,133,252,1) 48%, rgba(15,197,252,1) 100%);'><a href='./actualizarLibro.php?idLibro=$libro[idLibro]'>Actualizar</a></td>";
                    echo "<td style='background: linear-gradient(90deg, rgba(83,91,255,1) 21%, rgba(48,133,252,1) 48%, rgba(15,197,252,1) 100%);'><a href='?borrar={$libro['idLibro']}'>Borrar</a></td>";
                    echo "</tr>";
                }
                ?>
        </table>
    </main>
</body>

</html>