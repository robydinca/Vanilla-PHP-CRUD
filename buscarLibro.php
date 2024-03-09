<?php
require_once "./config.php";
require_once "Libros.php";
require_once "Autores.php";


$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = ''; 
$libros = new Libros($conexion);
$autores = new Autores($conexion);


if (isset($_GET['borrar'])) {
    $consulta = "DELETE FROM `libros` WHERE `idLibro` = '$_GET[borrar]'";
    $conexion->query($consulta);
    $conexion->close();
    header("Location:./buscarLibro.php");
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
require_once "Seguridad.php";
$seguridad = new Seguridad();
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
        <h2>Buscar libro</h2>
        <form action="buscarLibro.php" method="post">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo">
            <br>
            <label for="genero">Género</label>
            <select name="genero">
                <option value="">Todos los géneros</option>
                <option>Narrativa</option>
                <option>Lirica</option>
                <option>Teatro</option>
                <option>Cientifico-Tecnico</option>
            </select>
            <br>
            <label for="autor">Autor</label>
            <select name="idAutor">
                <?php
                $todosLosAutores = $autores->consultarAutor();
                echo "<option value=''>Todos los autores</option>";
                foreach ($todosLosAutores as $autor) {
                    echo "<option value='" . $autor['idAutor'] . "'>" . $autor['nombre'] . " " . $autor['apellidos'] . "</option>";
                }
                ?>
            </select>
            <br>
            <input type="submit" value="Buscar" class="boton">
        </form>

        <?php
        if (isset($_POST['titulo']) || isset($_POST['genero']) || isset($_POST['autor'])) {
            $titulo = $_POST['titulo'];
            $genero = $_POST['genero'];
            $autor = $_POST['idAutor'];

            $librosEncontrados = $libros->consultarCampos($titulo, $genero, $autor);
            if (!empty($librosEncontrados)) {
                $mensaje = "Libro encontrado";
            } else {
                $mensaje = "No se encontraron libros";
            }
        }

        ?>

        <div class="resultado">
            <?php
            if (isset($mensaje)) {
                echo "<p>$mensaje</p>";
            }

            if (isset($librosEncontrados)) {
                echo "<table border='2px'>";
                echo "<tr>";
                echo "<th>Id</th>";
                echo "<th>Título</th>";
                echo "<th>Género</th>";
                echo "<th>Nombre autor</th>";
                echo "<th>Número de páginas</th>";
                echo "<th>Número de ejemplares</th>";
                echo "<th>Actualizar</th>";
                echo "<th>Eliminar</th>";

                foreach ($librosEncontrados as $libro) {
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
                    echo "<td><a href='./actualizarLibro.php?idLibro=" . $libro['idLibro'] . "'>Actualizar</a></td>";
                    echo "<td><a href='?borrar={$libro['idLibro']}'>Borrar</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            }

            ?>
        </div>
    </main>

</body>

</html>