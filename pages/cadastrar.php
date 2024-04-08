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
</head>
<body>
    <form method="post" action="../cadastro.php" class="form" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirmação de Senha:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
        </div>
        <div class="button">
            <button type="submit">Cadastrar</button>
        </div>
    </form>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password != confirmPassword) {
                alert("As senhas não coincidem!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
