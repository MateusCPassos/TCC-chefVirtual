<?php
session_start();

require_once "../config/conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  $sql = "SELECT * FROM usuario WHERE email = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    if (password_verify($password, $result['password'])) {
      $_SESSION["loggedin"] = true;
      header("Location: index.php");
      exit;
    } else {
      echo "Senha incorreta.";
    }
  } else {
    echo "E-mail não encontrado.";
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
  <form method="post" action="">
    E-mail: <input type="email" name="email" required><br>
    Senha: <input type="password" name="password" required><br>
    <input type="submit" value="Entrar">
  </form>
  <br>
  <a href="cadastrar.php">Cadastrar</a>
</body>

</html>
