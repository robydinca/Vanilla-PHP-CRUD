<?php
require_once "./config.php";
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
$mensajeImagen = '';
$libros = new libros($conexion);
$datosLibro = $libros->consultarLibro($_GET['idLibro']);

if (isset($_POST['Actualizar'])) {
    $datosLibro = array(
        'idLibro' => $_POST['idLibro'],
        'titulo' => $_POST['titulo'],
        'genero' => $_POST['genero'],
        'idAutor' => $_POST['idAutor'], 
        'numeroPaginas' => $_POST['numeroPaginas'],
        'numeroEjemplares' => $_POST['numeroEjemplares']
    );

    $libros->actualizaLibro($datosLibro);
    $mensaje = "Libro insertado correctamente";
} else {
    $mensaje = "Por favor, complete todos los campos del formulario.";
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
    <form method="POST" action="">
        <input type="hidden" name="idLibro" value="<?php echo $_GET['idLibro']; ?>">
        <label for="titulo">Titulo</label>
        <input type="text" name="titulo" id="titulo" value="<?php echo $datosLibro['titulo'] ?>">
        <br>
        <label for="genero">Genero</label>
        <select name="genero" value="<?php echo $datosLibro['genero'] ?>">
            <option>Narrativa</option>
            <option>Lirica</option>
            <option>Teatro</option>
            <option>Cientifico-Tecnico</option>
        </select>
        <br>
        <label for="idAutor">Autor</label>
        <select name="idAutor" value="<?php echo $datosLibro['idAutor'] ?>">
            <?php
            require_once "Autores.php";
            $autores = new Autores($conexion);
            $todosLosAutores = $autores->consultarAutor();
            foreach ($todosLosAutores as $autor) {
                echo "<option value='" . $autor['idAutor'] . "'>" . $autor['nombre'] . " " . $autor['apellidos'] . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="numeroPaginas">Número de páginas</label>
        <input type="number" name="numeroPaginas" id="numeroPaginas" value="<?php echo $datosLibro['numeroPaginas'] ?>">
        <br>
        <label for="numeroEjemplares">Número de ejemplares</label>
        <input type="number" name="numeroEjemplares" id="numeroEjemplares" value="<?php echo $datosLibro['numeroEjemplares'] ?>">
        <br>
        <input type="submit" name="Actualizar" value="Actualizar" class="boton">
        <?php
        if (isset($mensaje)) {
            echo "<p>$mensaje</p>";
        }
        ?>

</body>

</html>
<?php
$conexion->close();
?>