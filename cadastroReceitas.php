<?php
session_start();
require_once "config/conecta.php";
    //verfica se o formulario de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameRecipe = isset($_POST["nameRecipe"]) ? trim($_POST["nameRecipe"]) : '';
    $modePreparation = isset($_POST["modePreparation"]) ? trim($_POST["modePreparation"]) : '';
    $price = isset($_POST["price"]) ? trim($_POST["price"]) : '';
    $timePreparation = isset($_POST["timePreparation"]) ? trim($_POST["timePreparation"]) : '';
    $comments = isset($_POST["comments"]) ? trim($_POST["comments"]) : '';
    $category = isset($_POST["category"]) ? $_POST["category"] : '';
    $ingredients = isset($_POST["ingredients"]) ? $_POST["ingredients"] : array();
    $materials = isset($_POST["materials"]) ? $_POST["materials"] : array();

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (empty($nameRecipe) || empty($modePreparation) || empty($price) || empty($timePreparation) || empty($comments) || empty($category)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        $uuid = uniqid(); // Gera um UUID único

        // Insere o novo prato com UUID
        $sql = "INSERT INTO prato (uuid, nome, custo, tempoPreparo, observacoes, categoria_uuid, usuario_uuid) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Verifica se a sessão está iniciada e se o UUID do usuário está definido
        if (isset($_SESSION["uuid"])) {
            if ($stmt->execute([$uuid, $nameRecipe, $price, $timePreparation, $comments, $category, $_SESSION["uuid"]])) {
                // Insere os ingredientes associados ao prato
                /*foreach ($ingredients as $ingredient_uuid => $quantity) {
                    $sql = "INSERT INTO prato_has_indredientes (prato_uuid, indredientes_uuid, quantidade) VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    if (!$stmt->execute([$uuid, $ingredient_uuid, $quantity])) {
                        echo "Erro ao cadastrar os ingredientes.";
                    }
                }

                // Insere os materiais associados ao prato
                foreach ($materials as $material_uuid) {
                    $sql = "INSERT INTO materiais_has_prato (materiais_uuid, prato_uuid) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    if (!$stmt->execute([$material_uuid, $uuid])) {
                        echo "Erro ao cadastrar os materiais.";
                    }
                }*/

                echo "Receita cadastrada com sucesso.";
            } else {
                echo "Erro ao cadastrar a receita.";
            }
        } else {
            echo "Erro: Sessão não iniciada.";
        }
    }
}

$pdo = null;

/*retirar os campos indredites e materias
    fazer uma listagem para os indredientes e materias

    trocar uuid por id comum com auto incremento 
    */
?>
