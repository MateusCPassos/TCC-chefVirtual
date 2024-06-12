<?php
session_start();
require_once "../config/conecta.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
  $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

  $sql = "SELECT * FROM usuario WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    if (password_verify($password, $user['senha'])) {
      $_SESSION["loggedin"] = true;
      $_SESSION["id"] = $user['id'];
      header("Location: index.php");
      exit;
    } else {
      $error = "E-mail ou senha incorretos.";
    }
  } else {
    $error = "E-mail ou senha incorretos.";
  }
}
?>

<?php
include "header.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../css/login.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
  <link rel="shortcut icon" href="../assets/img/icone.png">
</head>

<body>
  <h1>Login</h1>
  <?php if (!empty($error)) : ?>
    <script>
      alert("<?php echo $error; ?>");
    </script>
  <?php endif; ?>
  <form method="post" action="" class="form">
    <div class="form-group">
      <label for="email" class="input-label">E-mail:</label>
      <div class="input-container">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" id="email" name="email" required class="input-field" placeholder="ex: joaosilva@gmail.com">
      </div>
    </div>
    <div class="form-group">
      <label for="password" class="input-label">Senha:</label>
      <div class="input-container">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" id="password" name="password" required class="input-field" placeholder="ex: Joao1234">
      </div>
    </div>
    <div class="buttonRecipe">
      <button type="submit" class="buttonRecipe">Login</button>
    </div>
  </form>
  <div class="cadastrar">
    <p>Não possui cadastro? <a href="cadastrar.php">Cadastrar</a></p>
  </div>
  <?php
  require_once 'footer.php';
?>
</body>

</html>
