<?php
class Conexion {
    private static $conexion = null;

    public static function getConexion() {
        if (self::$conexion === null) {
            try {
                $host = 'localhost';
                $db = 'moonjeans';
                $usuario = 'root';
                $contrasena = '';
                $puerto = '3306';

                self::$conexion = new PDO("mysql:host=$host;port=$puerto;dbname=$db;charset=utf8", $usuario, $contrasena);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}
?>
