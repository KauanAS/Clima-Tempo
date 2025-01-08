<?php
class DatabaseConnection {
    private static $instance = null;
    public $conn;

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'climatempo';

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Falha na conexão: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }
}
?>
