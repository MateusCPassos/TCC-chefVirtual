<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <form method="post" action="../cadastro.php">
        Nome: <input type="text" name="name" required><br>
        E-mail: <input type="email" name="email" required><br>
        Senha: <input type="password" name="password" required><br> <!-- Adicionado 'required' -->
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
