<?php
require_once "header.php"
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
    <h1>cadastrar</h1>
    <form method="post" action="../cadastro.php" class="form" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="name" class="input-label">Nome:</label>
            <div class="input-container">
                <input type="text" id="name" name="name" required class="input-field" placeholder="ex: João Silva">
                <i class="fas fa-user input-icon"></i>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="input-label">E-mail:</label>
            <div class="input-container">
                <input type="email" id="email" name="email" required class="input-field" placeholder="ex: joaosilva@gmail.com">
                <i class="fas fa-envelope input-icon"></i>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="input-label">Senha:</label>
            <div class="input-container">
                <input type="password" id="password" name="password" required class="input-field" placeholder="ex: Joao1234">
                <i class="fas fa-lock input-icon"></i>
            </div>
        </div>
        <div class="form-group">
            <label for="confirmPassword" class="input-label">Confirmação de Senha:</label>
            <div class="input-container">
                <input type="password" id="confirmPassword" name="confirmPassword" required class="input-field" placeholder="ex: Joao1234">
                <i class="fas fa-lock input-icon"></i>
            </div>
        </div>
        <div class="button">
            <button type="submit">Cadastrar</button>
        </div>
    </form>

    <div class="login">
        <p> possui cadastro?</p><a href="login.php">Login</a>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var email = document.getElementById("email").value;

            if (password != confirmPassword) {
                alert("As senhas não coincidem!");
                return false;
            }

            if (password.length < 6) {
                alert("A senha deve ter pelo menos 6 caracteres!");
                return false;
            }
        }
    </script>
</body>

</html>