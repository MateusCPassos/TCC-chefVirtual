<?php
require_once "config/conecta.php";
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: pages/login.php");
    exit;
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;
    $foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : '';

    // Verifica se o email já existe, excluindo o email atual do usuário
    $sql_email_check = "SELECT id FROM usuario WHERE email = ? AND id != ?";
    $stmt_email_check = $pdo->prepare($sql_email_check);
    $stmt_email_check->execute([$email, $user_id]);
    if ($stmt_email_check->fetch()) {
        echo "<p class='error'>O email já está em uso por outro usuário.</p>";
    } else {
        // Verifica se uma nova foto foi enviada
        if (!empty($_FILES['foto']['name'])) {
            $target_dir = "../arquivosUsuario/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto = $target_file;
            }
        }

        // Atualiza os dados do usuário no banco de dados
        if ($senha) {$sql_update = "UPDATE usuario SET nome = ?, email = ?, senha = ?, fotoUsuariocol = ? WHERE id = ?";
          $stmt_update = $pdo->prepare($sql_update);
          if ($stmt_update->execute([$nome, $email, $senha, $foto, $user_id])) {
              echo "<p class='success'>Conta atualizada com sucesso!</p>";
              // Atualiza os dados na sessão
              $_SESSION['nome'] = $nome;
              $_SESSION['email'] = $email;
              $_SESSION['foto'] = $foto;
          } else {
              echo "<p class='error'>Erro ao atualizar a conta. Tente novamente.</p>";
          }
      } else {
          $sql_update = "UPDATE usuario SET nome = ?, email = ?, fotoUsuariocol = ? WHERE id = ?";
          $stmt_update = $pdo->prepare($sql_update);
          if ($stmt_update->execute([$nome, $email, $foto, $user_id])) {
              echo "<p class='success'>Conta atualizada com sucesso!</p>";
              // Atualiza os dados na sessão
              $_SESSION['nome'] = $nome;
              $_SESSION['email'] = $email;
              $_SESSION['foto'] = $foto;
          } else {
              echo "<p class='error'>Erro ao atualizar a conta. Tente novamente.</p>";
          }
      }
  }
}
?>

           
