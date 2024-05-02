<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $materials = isset($_POST["materials"]) ? $_POST["materials"] : array();
    $recipe_id = isset($_POST["recipe_id"]) ? $_POST["recipe_id"] : '';

    // Verifica se a sessão está iniciada
    if (isset($_SESSION["id"]) && !empty($recipe_id)) {
        // Prepara a query para inserir os materiais
        $sql = "INSERT INTO materiais_has_prato (materiais_id, prato_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);

        // Insere os materiais um por um
        foreach ($materials as $material_id) {
            if (!$stmt->execute([$material_id, $recipe_id])) {
                echo "Erro ao cadastrar o material com ID: " . $material_id;
            }
        }

        echo "Materiais cadastrados com sucesso.";
    } else {
        echo "Erro: Sessão não iniciada ou ID da receita não definido.";
    }
}

$pdo = null;
?>
