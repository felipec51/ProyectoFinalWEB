<?php
// Archivo: conexion.php
class Conexion
{
    public static function Conectar()
    {
        define('servidor', 'localhost'); 
        define('nombre_bd', 'FinWeb'); // Ajustar según configuración local colocan el nombre de la bd
        define('usuario', 'root'); // Ajustar según configuración local colocan su usuario
        define('passw', ''); // Ajustar según configuración local en mi caso era esa porque estoy en linux y
        // pide contraseña en windows si configuraron xampp sin contraseña poner '' 
        $opces = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        try {
            $conexion = new PDO("mysql:host=" . servidor . "; dbname=" . nombre_bd, usuario, passw, $opces);
            // Configuración de errores CRUCIAL para depuración
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return $conexion;
        } catch (Exception $e) {
            die("El error de Conexión es: " . $e->getMessage());
        }
    }
}
?>