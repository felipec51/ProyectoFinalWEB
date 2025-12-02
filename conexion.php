<?php
// Archivo: conexion.php
class Conexion
{
    public static function Conectar()
    {
        define('servidor', 'localhost'); 
        define('nombre_bd', 'mydb'); 
        define('usuario', 'root'); 
        define('passw', ''); 

        $opces = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        try {
            $conexion = new PDO("mysql:host=" . servidor . "; dbname=" . nombre_bd, usuario, passw, $opces);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return $conexion;
        } catch (Exception $e) {
            die("El error de Conexión es: " . $e->getMessage());
        }
    }
}
?>