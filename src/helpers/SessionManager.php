<?php
namespace App\Helpers;

class SessionManager {
    public static function iniciarSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function cerrarSesion() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
            $_SESSION = [];
        }
    }

    public static function estaAutenticado() {
        self::iniciarSesion();
        return isset($_SESSION['user_id']);
    }

    public static function obtenerUsuario($campo = null) {
        self::iniciarSesion();
        if ($campo && isset($_SESSION[$campo])) {
            return $_SESSION[$campo];
        }
        return $_SESSION;
    }

    public static function guardarUsuario($datos) {
        self::iniciarSesion();
        foreach ($datos as $clave => $valor) {
            $_SESSION[$clave] = $valor;
        }
    }
}