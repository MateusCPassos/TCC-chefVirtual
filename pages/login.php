<?php
session_start();

require_once "../config/conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$email])) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Autenticação bem-sucedida, inicia a sessão
                $_SESSION["loggedin"] = true;
                $_SESSION["user_uuid"] = $user['uuid']; // UUID do usuário
                $_SESSION["user_email"] = $user['email'];
                
                // Redireciona para a página de perfil ou qualquer outra página após o login
                header("Location: profile.php");
                exit;
            } else {
                $login_error = "Senha ou E-mail incorreta.";
            }
        } 
    } 
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        E-mail: <input type="email" name="email" required><br>
        Senha: <input type="password" name="password" required><br>
        <input type="submit" value="Entrar">
    </form>
    <?php if (isset($login_error)) echo "<p>$login_error</p>"; ?>
    <p>Não tem uma conta? <a href="cadastrar.php">Cadastrar-se</a></p>
</body>
</html>
