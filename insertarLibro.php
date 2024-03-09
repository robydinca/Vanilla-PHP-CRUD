<?php
require_once "config.php";
require_once "Libros.php";
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

$libros = new libros($conexion);
if (isset($_POST['insertar'])) {


    if (!empty($_POST['titulo']) && !empty($_POST['genero']) && !empty($_POST['idAutor']) && !empty($_POST['numeroPaginas']) && !empty($_POST['numeroEjemplares'])) {
        $datosLibro = array(
            'titulo' => $_POST['titulo'],
            'genero' => $_POST['genero'],
            'idAutor' => $_POST['idAutor'],
            'numeroPaginas' => $_POST['numeroPaginas'],
            'numeroEjemplares' => $_POST['numeroEjemplares']
        );

        $libros->insertar($datosLibro);
        $mensaje = "Libro insertado correctamente";
    } else {
        $mensaje = "Por favor, complete todos los campos del formulario.";
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
        <h2>Insertar libro</h2>
        <form method="POST" action="" class="insertarLibro">
            <label for="titulo">Titulo</label>
            <input type="text" name="titulo" id="titulo" required>
            <br>
            <label for="genero">Genero</label>
            <select name="genero">
                <option>Narrativa</option>
                <option>Lirica</option>
                <option>Teatro</option>
                <option>Cientifico-Tecnico</option>
            </select>
            <br>
            <div class="añadirAutor">
                <label for="idAutor">Autor</label>
                <select name="idAutor">
                    <?php
                    require_once "Autores.php";
                    $autores = new Autores($conexion);
                    $todosLosAutores = $autores->consultarAutor();
                    foreach ($todosLosAutores as $autor) {
                        echo "<option value='" . $autor['idAutor'] . "'>" . $autor['nombre'] . " " . $autor['apellidos'] . "</option>";
                    }
                    ?>
                </select>
                <button class="botonInsertar" type="button" onclick="window.location.href='insertarAutor.php'">+</button>
            </div>

            <br>
            <label for="numeroPaginas">Numero de paginas</label>
            <input type="number" name="numeroPaginas" id="numeroPaginas" required>
            <br>
            <label for="numeroEjemplares">Numero de ejemplares</label>
            <input type="number" name="numeroEjemplares" id="numeroEjemplares" required>
            <br>
            <input type="submit" value="Insertar" name="insertar" class="boton">
        </form>


        <?php
        echo $mensaje;
        $conexion->close();
        ?>
    </main>
</body>

</html>