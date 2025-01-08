<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'DatabaseConnection.php';

$db = DatabaseConnection::getInstance();

$user_id = $_SESSION['user_id'];

$sql = "SELECT cidade FROM historico_pesquisas WHERE cd_pessoa = ? ORDER BY id DESC LIMIT 4";
$stmt = $db->conn->prepare($sql);

if ($stmt === false) {
    die('Erro ao preparar a consulta: ' . $db->conn->error);
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute() === false) {
    die('Erro ao executar a consulta: ' . $stmt->error);
}

$result = $stmt->get_result();
$ultimasPesquisas = [];
while ($row = $result->fetch_assoc()) {
    $ultimasPesquisas[] = $row['cidade'];
}

$stmt->close();

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clima agora!</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/styles.css" />
    <script src="scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo(a), <?php echo htmlspecialchars($user_name); ?></h1>
        <div class="form">
            <h3>Confira o clima de uma cidade:</h3>
            <div class="form-input-container">
                <input type="text" placeholder="Digite o nome da cidade" id="city-input" />
                <button id="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            <div id="weather-data" class="hide">
                <h2><i class="fa-solid fa-location-dot"></i> <span id="city"></span> <img id="country" alt="Country flag"></h2>
                <p id="temperature"><span></span>&deg;C</p>
                <div id="description-container">
                    <p id="description"></p>
                    <img id="weather-icon" src="" alt="Condições atuais">
                </div>
                <div id="details-container">
                    <p id="umidity"><i class="fa-solid fa-droplet"></i> <span></span></p>
                    <p id="wind"><i class="fa-solid fa-wind"></i> <span></span></p>
                </div>
            </div>
            <div id="error-message" class="hide">
                <p>Não foi possível encontrar o clima de uma cidade com este nome.</p>
            </div>
            <div id="loader" class="hide">
                <i class="fa-solid fa-spinner"></i>
            </div>
            </br></br>
            <h2>Últimas 4 cidades pesquisadas:</h2>
            <div id="suggestions">
                <?php if (empty($ultimasPesquisas)): ?>
                    <p>Você ainda não pesquisou nenhuma cidade.</p>
                <?php else: ?>
                    <?php foreach ($ultimasPesquisas as $cidade): ?>
                        <button onclick="pesquisarCidade('<?php echo htmlspecialchars($cidade); ?>')">
                            <?php echo htmlspecialchars($cidade); ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('search').addEventListener('click', function() {
            const city = document.getElementById('city-input').value.trim();  // Pegue o valor do campo de entrada

            if (city) {
                fetch('salvar_pesquisa.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'cidade=' + encodeURIComponent(city)
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);  // Verifique o console para ver a resposta
                    if (data.includes("sucesso")) {
                        location.reload();  // Recarrega a página após salvar
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                console.log("Cidade não fornecida.");
            }
        });

        function pesquisarCidade(cidade) {
            document.getElementById('city-input').value = cidade;
            document.getElementById('search').click();
        }
    </script>
</body>
</html>
