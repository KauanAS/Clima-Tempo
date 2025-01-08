<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cadastro/CSS/style.css">
    <title>Formulario de cadastro</title>
</head>
<body>
    <div class="container">
        <div class="form-imagem">
            <img src="cadastro/IMG/Imagemsvg.svg">
        </div>
        <div class="form">
            <form id="signupForm" action="cadastro.php" method="POST">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                    <div class="login-button">
                        <a href="PAGINA_DE_LOGIN/login.php"><button type="button">Entrar</button></a>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="firstname">Primeiro nome</label>
                        <input id="firstname" type="text" name="firstname" placeholder="Digite seu primeiro nome" required>
                    </div>

                    <div class="input-box">
                        <label for="lastname">Sobrenome</label>
                        <input id="lastname" type="text" name="lastname" placeholder="Digite seu Sobrenome" required>
                    </div>

                    <div class="input-box">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-box">
                        <label for="number">Celular</label>
                        <input id="number" type="tel" name="number" placeholder="(xx)xxxxx-xxxx" required>
                    </div>

                    <div class="input-box">
                        <label for="password">Digite sua senha</label>
                        <input id="password" type="password" name="password" placeholder="Digite sua senha" required>
                    </div>

                    <div class="input-box">
                        <label for="confirmpassword">Confirme sua Senha</label>
                        <input id="confirmpassword" type="password" name="confirmpassword" placeholder="Digite sua senha" required>
                    </div>
                </div>
                <div class="continue-button">
                    <button type="submit">Continuar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirmpassword').value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('As senhas não coincidem.');
            }
        });
    </script>
</body>
</html>
