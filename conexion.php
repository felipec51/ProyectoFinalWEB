<?php
class Conexion {

    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "FinWeb";

    public function conectar() {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>
