<?php
require_once "Seguridad.php";

$seguridad = new Seguridad();

if (!$seguridad->estaAutenticado()) {
    header("Location: login.php");
    exit;
}

$rolUsuario = $seguridad->obtenerRol();
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <?php
    if (!file_exists("config.php")) {
        header("Location: install.php");
    } else if ($rolUsuario === 'admin') {
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
    <main style="height:80vh; display:flex; justify-content:center; margin-right:140px; margin-top:20px;">
        <canvas id="miGrafico"></canvas>
    </main>
    <script src="./graficos.js" type="module"></script>
</body>

</html>