<?php
require_once "Seguridad.php";

$seguridad = new Seguridad(); // Instanciar la clase Seguridad

// Cerrar la sesión (logout)
$seguridad->logout();

// Redirigir a la página de inicio de sesión (login.php)
header("Location: login.php");
exit;
?>
