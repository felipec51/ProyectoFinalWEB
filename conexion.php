<?php
class Conexion {
    private $host = "localhost";
    private $db   = "FinWeb";
    private $user = "root";
    private $pass = "";
    private $charset = "utf8";

    public function Conectar() {
        try {
            $conexion = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $pdo = new PDO($conexion, $this->user, $this->pass, $options);
            return $pdo;

        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit;
        }
    }
}
?>
