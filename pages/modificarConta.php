<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Conta</title>
  <link rel="stylesheet" href="../css/modificarConta.css">
  <link rel="shortcut icon" href="../assets/img/icone.png">

</head>
<body>
  <?php
  require_once "../config/conecta.php";
  session_start();
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
      header("location: login.php");
      exit;

  }

  $user_id = $_SESSION['id'];
  $sql = "SELECT * FROM usuario WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  $nome = isset($user['nome']) ? $user['nome'] : '';
  $email = isset($user['email']) ? $user['email'] : '';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $nome = $_POST['nome'];
      $email = $_POST['email'];
      $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : $user['senha'];
      $foto = $user['fotoUsuariocol'];

      // Verifica se o email é diferente do email atual do usuário
      if ($email !== $user['email']) {
          // Verifica se o email já existe
          $sql_email_check = "SELECT id FROM usuario WHERE email = ? AND id != ?";
          $stmt_email_check = $pdo->prepare($sql_email_check);
          $stmt_email_check->execute([$email, $user_id]);
          if ($stmt_email_check->fetch()) {
              echo "<p class='error'>O email já está em uso por outro usuário.</p>";
              exit; // Encerra o script para evitar a atualização com email duplicado
          }
      }

      // Verifica se uma nova foto foi enviada
      if (!empty($_FILES['foto']['name'])) {
          $target_dir = "../arquivosUsuario/";
          $target_file = $target_dir . basename($_FILES["foto"]["name"]);
          move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
          $foto = $target_file;
      }

      $sql_update = "UPDATE usuario SET nome = ?, email = ?, senha = ?, fotoUsuariocol = ? WHERE id = ?";
      $stmt_update = $pdo->prepare($sql_update);
      if ($stmt_update->execute([$nome, $email, $senha, $foto, $user_id])) {
          echo "<p class='success'>Conta atualizada com sucesso!</p>";
          $_SESSION['email'] = $email;
      } else {
          echo "<p class='error'>Erro ao atualizar a conta. Tente novamente.</p>";
      }
  }
  require_once "header.php";

  ?>
  <div class="espaco"></div>
  <div class="container">
    <h2>Modificar Conta</h2>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
      </div>
      <div class="form-group">
        <label for="senha">Nova Senha (deixe em branco para não alterar):</label>
        <input type="password" id="senha" name="senha">
      </div>
      <div class="form-group">
        <label for="foto">Foto de Perfil:</label>
        <input type="file" id="foto" name="foto" accept="image/*">
      </div>
      <button type="submit">Atualizar Conta</button>
    </form>
  </div>

  <?php
  require_once 'footer.php';
  ?>
</body>
</html>
