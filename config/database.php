<?php
namespace App\Config;

class Database {
    private static $host = 'localhost';
    private static $dbname = 'proyecto_login';
    private static $username = 'root';
    private static $password = '';
    private static $conexion = null;

    public static function conectar() {
        try {
            if (self::$conexion === null) {
                // Usar PDO con opciones de error
                $opciones = [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ];

                self::$conexion = new \PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname, 
                    self::$username, 
                    self::$password,
                    $opciones
                );
            }
            return self::$conexion;
        } catch (\PDOException $e) {
            // Mejorar el manejo de errores
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            throw new \Exception("No se pudo conectar a la base de datos");
        }
    }

    public static function desconectar() {
        self::$conexion = null;
    }
}