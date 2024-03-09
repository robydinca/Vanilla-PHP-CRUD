<?php
require_once "config.php";
require_once "Usuarios.php";
require_once "Seguridad.php"; // Asegúrate de incluir el archivo de la clase Seguridad

$mensaje = "";
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT) or die("Error de conexión: " . $conexion->connect_error);

$seguridad = new Seguridad(); // Crear una instancia de la clase Seguridad

if (!$seguridad->tienePermiso('admin')) {
    header("Location: ./index.php"); // Redirigir si no tiene permiso de administrador
    exit(); // Finalizar la ejecución del script
}

$usuarios = new Usuarios($conexion);

if (isset($_GET['borrar'])) {
    $consulta = "DELETE FROM `usuarios` WHERE `login` = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $_GET['borrar']);
    $stmt->execute();
    $stmt->close();
    header("Location: ./gestionUsuarios.php");
    exit();
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
        <h2 class="title">Listado de usuarios</h2><br>
        <a href="registrarUsuario.php" class="boton">Registrar nuevo usuario</a>
        <table border="2px">
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Login</th>
                <th>Password</th>
                <th>Rol</th>
                <th>Actualizar</th>
                <th>Eliminar</th>

                <?php
                require_once "Usuarios.php";
                $usuarios = new Usuarios($conexion);
                $listaUsuarios = $usuarios->consultarUsuario();

                foreach ($listaUsuarios as $usuario) {
                    echo "<tr>";
                    echo "<td>$usuario[nombre]</td>";
                    echo "<td>$usuario[apellidos]</td>";
                    echo "<td>$usuario[login]</td>";
                    echo "<td>$usuario[password]</td>";
                    echo "<td>$usuario[rol]</td>";
                    echo "<td style='background: linear-gradient(90deg, rgba(83,91,255,1) 21%, rgba(48,133,252,1) 48%, rgba(15,197,252,1) 100%);'><a href='./actualizarUsuario.php?login=$usuario[login]'>Actualizar</a></td>";
                    echo "<td style='background: linear-gradient(90deg, rgba(83,91,255,1) 21%, rgba(48,133,252,1) 48%, rgba(15,197,252,1) 100%);'><a href='?borrar={$usuario['login']}'>Borrar</a></td>";
                    echo "</tr>";
                }
                ?>
        </table>
    </main>
</body>

</html>