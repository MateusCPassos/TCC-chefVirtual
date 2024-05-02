<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de edição foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recipe_id"])) {
    $recipe_id = $_POST["recipe_id"];
    $name = $_POST["name"];
    $modePreparation = $_POST["modePreparation"]; // Verifique se o nome do campo está correto
    $cost = $_POST["cost"];
    $preparation_time = $_POST["preparation_time"];
    $observations = $_POST["observations"];
    $category_id = $_POST["category"];

    // Atualiza os dados da receita no banco de dados
    $sql = "UPDATE prato SET nome = ?, modoPreparo = ?, custo = ?, tempoPreparo = ?, observacoes = ?, categoria_id = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$name, $modePreparation, $cost, $preparation_time, $observations, $category_id, $recipe_id])) {
        header("Location: pages/minhasReceitas.php");
        exit;
    } else {
        echo "Erro ao atualizar a receita.";
    }
} else {
    echo "Requisição inválida.";
}

$pdo = null;
?>
