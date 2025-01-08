<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

require 'DatabaseConnection.php';  // Verifique se este arquivo está correto

$db = DatabaseConnection::getInstance();
$user_id = $_SESSION['user_id'];

// Verifica se 'cidade' está presente no corpo da requisição POST
if (!isset($_POST['cidade'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "Cidade não fornecida.";
    exit();
}

$cidade = $_POST['cidade'];

// Insere a cidade pesquisada no banco de dados
$sql = "INSERT INTO historico_pesquisas (cd_pessoa, cidade) VALUES (?, ?)";
$stmt = $db->conn->prepare($sql);

if ($stmt === false) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Erro ao preparar a consulta: " . $db->conn->error;
    exit();
}

$stmt->bind_param("is", $user_id, $cidade);

if ($stmt->execute() === false) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Erro ao executar a consulta: " . $stmt->error;
    exit();
}

$stmt->close();
echo "sucesso";  // Resposta para indicar sucesso ao JavaScript
?>
