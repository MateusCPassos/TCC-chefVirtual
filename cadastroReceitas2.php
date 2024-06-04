<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se a sessão está iniciada e se o usuário é o dono da receita
    if (isset($_SESSION["id"]) && !empty($_POST["recipe_id"])) {
        $recipe_id = $_POST["recipe_id"];
        $user_id = $_SESSION["id"];

        // Remoção de material
        if (isset($_POST["remove_material_id"])) {
            $material_id = $_POST["remove_material_id"];

            // Verifica se o material está associado ao prato
            $sql_check_material = "SELECT * FROM materiais_has_prato WHERE materiais_id = ? AND prato_id = ?";
            $stmt_check_material = $pdo->prepare($sql_check_material);
            $stmt_check_material->execute([$material_id, $recipe_id]);

            if ($stmt_check_material->rowCount() > 0) {
                // Remove o material associado à receita
                $sql_delete_material = "DELETE FROM materiais_has_prato WHERE materiais_id = ? AND prato_id = ?";
                $stmt_delete_material = $pdo->prepare($sql_delete_material);
                if ($stmt_delete_material->execute([$material_id, $recipe_id])) {
                    echo "Material removido com sucesso.";
                } else {
                    echo "Erro ao remover o material.";
                }
            } else {
                echo "Este material não está associado a esta receita.";
            }
        } else {
            // Adição de material
            $material_nome = isset($_POST["material_nome"]) ? $_POST["material_nome"] : '';

            // Busca o ID do material com base no nome fornecido
            $sql_select_material_id = "SELECT id FROM materiais WHERE nomeMaterial = ?";
            $stmt_select_material_id = $pdo->prepare($sql_select_material_id);
            $stmt_select_material_id->execute([$material_nome]);
            $material_id_row = $stmt_select_material_id->fetch(PDO::FETCH_ASSOC);
            $material_id = $material_id_row['id'] ?? null;

            if ($material_id === null) {
                echo "Material não encontrado.";
            } else {
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
            }
        }
        
        // Redireciona de volta para a página de cadastro de materiais
        header("Location: pages/cadastrarReceitas2.php?recipe_id=" . $recipe_id);
        exit();
    } else {
        echo "Erro: Sessão não iniciada ou ID da receita não definido.";
    }
}

$pdo = null;
?>
