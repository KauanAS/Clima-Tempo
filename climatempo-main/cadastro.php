<?php
class DatabaseConnection {
    private $servername;
    private $username;
    private $password;
    private $database;
    public $conn;

    public function __construct($servername = "localhost", $username = "root", $password = "", $database = "climatempo") {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    private function connect() {
        // Conectar ao banco de dados
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        // Verificar a conexão
        if ($this->conn->connect_error) {
            die("Conexão falhou: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        // Fechar a conexão quando o objeto for destruído
        if ($this->conn && !$this->conn->connect_error) {
            $this->conn->close();
        }
    }
}

// Exemplo de uso:
$db = new DatabaseConnection();

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber dados do formulário
    $p_nome = $_POST['firstname'] ?? '';
    $s_nome = $_POST['lastname'] ?? '';
    $senha = $_POST['password'] ?? '';
    $confirm_senha = $_POST['confirmpassword'] ?? '';
    $email = $_POST['email'] ?? '';
    $celular = $_POST['number'] ?? '';

    // Verificar se as senhas coincidem
    if ($senha !== $confirm_senha) {
        die("As senhas não coincidem.");
    }

    try {
        // Iniciar a transação
        $db->conn->begin_transaction();

        // Usar prepared statements para inserir dados na tabela cadastro
        $stmt_cadastro = $db->conn->prepare("INSERT INTO cadastro (p_nome, s_nome, senha, email, celular) VALUES (?, ?, ?, ?, ?)");
        $stmt_cadastro->bind_param("sssss", $p_nome, $s_nome, $senha, $email, $celular);

        if ($stmt_cadastro->execute() === TRUE) {
            // Confirmar a transação
            $db->conn->commit();
            // Redirecionar para a página de vendas após o registro bem-sucedido
            header("Location: climatempo.html");
            exit();
        } else {
            // Reverter a transação em caso de erro
            $db->conn->rollback();
            echo "Erro ao criar registro do cliente: " . $stmt_cadastro->error;
        }

        // Fechar o statement
        $stmt_cadastro->close();
    } catch (Exception $e) {
        // Reverter a transação em caso de exceção
        $db->conn->rollback();
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $db->conn->close();
}
?>
