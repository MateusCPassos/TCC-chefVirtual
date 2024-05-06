<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verifica se a sessão está iniciada e se o usuário é o dono da receita
  if (isset($_SESSION["id"]) && !empty($_POST["recipe_id"])) {
    $recipe_id = $_POST["recipe_id"];
    $user_id = $_SESSION["id"];
    $ingrediente_id = isset($_POST["ingrediente_id"]) ? $_POST["ingrediente_id"] : '';
    $quantidade = isset($_POST["quantidade"]) ? $_POST["quantidade"] : '';

    // Verifica se o ingrediente já está associado ao prato
    $sql_check_ingrediente = "SELECT * FROM prato_has_indredientes WHERE indredientes_id = ? AND prato_id = ?";
    $stmt_check_ingrediente = $pdo->prepare($sql_check_ingrediente);
    $stmt_check_ingrediente->execute([$ingrediente_id, $recipe_id]);

    if ($stmt_check_ingrediente->rowCount() > 0) {
      echo "Este ingrediente já está associado a esta receita.";
    } else {
      // Insere o ingrediente associado à receita
      $sql_insert_ingrediente = "INSERT INTO prato_has_indredientes (indredientes_id, prato_id, quantidade) VALUES (?, ?, ?)";
      $stmt_insert_ingrediente = $pdo->prepare($sql_insert_ingrediente);
      if (!$stmt_insert_ingrediente->execute([$ingrediente_id, $recipe_id, $quantidade])) {
        echo "Erro ao cadastrar o ingrediente com ID: " . $ingrediente_id;
      } else {
        echo "<script>location.href='cadastroIngredientes.php?recipe_id=" . $recipe_id . "'</script>";
      }
    }
  } else {
    echo "Erro: Sessão não iniciada ou ID da receita não definido.";
  }
}

$pdo = null;
