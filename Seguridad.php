<?php

class Seguridad {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Iniciar la sesión si no está iniciada
        }
    }

    public function login($login, $rol) {
        $_SESSION['login'] = $login;
        $_SESSION['rol'] = $rol;
        // Puedes almacenar más información en la sesión si es necesario
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function estaAutenticado() {
        return isset($_SESSION['login']);
    }

    public function obtenerRol() {
        if ($this->estaAutenticado()) {
            return $_SESSION['rol'];
        }
        return null;
    }

    public function tienePermiso($rolRequerido) {
        if ($this->estaAutenticado() && $_SESSION['rol'] === $rolRequerido) {
            return true;
        }
        return false;
    }
}


?>
