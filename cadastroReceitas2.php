<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se a sessão está iniciada e se o usuário é o dono da receita
    if (isset($_SESSION["id"]) && !empty($_POST["recipe_id"])) {
        $recipe_id = $_POST["recipe_id"];
        $user_id = $_SESSION["id"];
        $material_id = isset($_POST["material_id"]) ? $_POST["material_id"] : '';

        // Verifica se o material já está associado ao prato
        $sql_check_material = "SELECT * FROM materiais_has_prato WHERE materiais_id = ? AND prato_id = ?";
        $stmt_check_material = $pdo->prepare($sql_check_material);
        $stmt_check_material->execute([$material_id, $recipe_id]);

        if ($stmt_check_material->rowCount() > 0) {
            echo "Este material já está associado a esta receita.";
        } else {
            // Insere o material associado à receita
            $sql_insert_material = "INSERT INTO materiais_has_prato (materiais_id, prato_id) VALUES (?, ?)";
            $stmt_insert_material = $pdo->prepare($sql_insert_material);
            if (!$stmt_insert_material->execute([$material_id, $recipe_id])) {
                echo "Erro ao cadastrar o material com ID: " . $material_id;
            } else {
                echo "<script>location.href='pages/cadastrarReceitas2.php?recipe_id=" . $recipe_id . "'</script>";
            }
        }
    } else {
        echo "Erro: Sessão não iniciada ou ID da receita não definido.";
    }
}

$pdo = null;
