<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameRecipe = isset($_POST["nameRecipe"]) ? trim($_POST["nameRecipe"]) : '';
    $modePreparation = isset($_POST["modePreparation"]) ? trim($_POST["modePreparation"]) : '';
    $price = isset($_POST["price"]) ? trim($_POST["price"]) : '';
    $timePreparation = isset($_POST["timePreparation"]) ? trim($_POST["timePreparation"]) : '';
    $comments = isset($_POST["comments"]) ? trim($_POST["comments"]) : '';
    $category = isset($_POST["category"]) ? $_POST["category"] : '';

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (empty($nameRecipe) || empty($modePreparation) || empty($price) || empty($timePreparation) || empty($comments) || empty($category)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Insere o novo prato com ID comum (auto incremento)
        $sql = "INSERT INTO prato (nome, modoPreparo, custo, tempoPreparo, observacoes, categoria_id, usuario_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Verifica se a sessão está iniciada e se o ID do usuário está definido
        if (isset($_SESSION["id"])) {
            if ($stmt->execute([$nameRecipe, $modePreparation, $price, $timePreparation, $comments, $category, $_SESSION["id"]])) {
                // Recupera o ID da receita recém inserida
                $recipe_id = $pdo->lastInsertId();

                // Redireciona para a segunda tela de cadastro, passando o ID da receita
                header("Location: pages/cadastrarReceitas2.php?recipe_id=" . $recipe_id);
                exit;
            } else {
                echo "Erro ao cadastrar a receita.";
            }
        } else {
            echo "Erro: Sessão não iniciada.";
        }
    }
}

$pdo = null;
?>
