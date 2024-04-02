<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <form method="post" action="../cadastro.php" onsubmit="return validarForm()">
        Nome: <input type="text" name="name" required><br>
        E-mail: <input type="email" name="email" required><br>
        Senha: <input type="password" id="password" name="password" required><br> 
        Confirmar Senha: <input type="password" id="confirm_password" required><br> 
        <button type="submit">Cadastrar</button>
    </form>

    <script>
        function validarForm() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            if (password != confirm_password) {
                alert("As senhas não coincidem!");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
