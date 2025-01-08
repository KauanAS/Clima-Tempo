<?php
class DatabaseConnection {
    private $conn;

    public function __construct() {
        $servername = "localhost";  // substitua com o seu servidor de banco de dados
        $username = "root";         // substitua com o seu nome de usuário do banco de dados
        $password = "";             // substitua com a sua senha do banco de dados
        $dbname = "climatempo";      // substitua com o nome do seu banco de dados

        // Criar conexão
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexão
        if ($this->conn->connect_error) {
            throw new Exception("Falha na conexão: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
