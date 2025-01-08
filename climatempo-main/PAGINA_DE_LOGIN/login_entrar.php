<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class DatabaseConnection {
    private $servername;
    private $username;
    private $password;
    private $database;
    public $conn;
    private static $instance = null;

    public function __construct($servername = "localhost", $username = "root", $password = "", $database = "climatempo") {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        if ($this->conn && !$this->conn->connect_error) {
            $this->conn->close();
        }
    }

    public function __clone() {}
    public function __wakeup() {}
}

$db = DatabaseConnection::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    $stmt_login = $db->conn->prepare("SELECT cd_pessoa, p_nome, s_nome FROM cadastro WHERE email = ? AND senha = ?");
    if ($stmt_login === false) {
        die("Erro ao preparar a consulta: " . $db->conn->error);
    }
    $stmt_login->bind_param("ss", $email, $senha);

    if ($stmt_login->execute() === false) {
        die("Erro ao executar a consulta: " . $stmt_login->error);
    }
    $result = $stmt_login->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['cd_pessoa'];
        $_SESSION['user_name'] = $user['p_nome'] . ' ' . $user['s_nome'];

        header("Location: climatempo.php");
        exit();
    } else {
        $error_message = "Email ou senha incorretos.";
        header("Location: login.html?error=" . urlencode($error_message));
        exit();
    }

    $stmt_login->close();
}
?>
