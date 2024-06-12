<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se a sessão está iniciada e se o usuário é o dono da receita
    if (isset($_SESSION["id"]) && !empty($_POST["recipe_id"])) {
        $recipe_id = $_POST["recipe_id"];
        $user_id = $_SESSION["id"];

        // Remoção de ingrediente
        if (isset($_POST["remove_ingrediente_id"])) {
            $ingrediente_id = $_POST["remove_ingrediente_id"];

            // Verifica se o ingrediente está associado ao prato
            $sql_check_ingrediente = "SELECT * FROM prato_has_indredientes WHERE indredientes_id = ? AND prato_id = ?";
            $stmt_check_ingrediente = $pdo->prepare($sql_check_ingrediente);
            $stmt_check_ingrediente->execute([$ingrediente_id, $recipe_id]);

            if ($stmt_check_ingrediente->rowCount() > 0) {
                // Remove o ingrediente da receita
                $sql_delete_ingrediente = "DELETE FROM prato_has_indredientes WHERE indredientes_id = ? AND prato_id = ?";
                $stmt_delete_ingrediente = $pdo->prepare($sql_delete_ingrediente);
                if ($stmt_delete_ingrediente->execute([$ingrediente_id, $recipe_id])) {
                    echo "Ingrediente removido com sucesso.";
                } else {
                    echo "Erro ao remover o ingrediente.";
                }
            } else {
                echo "Este ingrediente não está associado a esta receita.";
            }
        } else {
            // Adição de ingrediente
            $ingrediente_nome = isset($_POST["ingredientes_nome"]) ? $_POST["ingredientes_nome"] : '';
            $quantidade = isset($_POST["quantidade"]) ? $_POST["quantidade"] : '';

            // Busca ingrediente com base no nome fornecido
            $sql_select_ingrediente_id = "SELECT id FROM indredientes WHERE NomeIndrediente = ?";
            $stmt_select_ingrediente_id = $pdo->prepare($sql_select_ingrediente_id);
            $stmt_select_ingrediente_id->execute([$ingrediente_nome]);
            $ingrediente_id_row = $stmt_select_ingrediente_id->fetch(PDO::FETCH_ASSOC);
            $ingrediente_id = $ingrediente_id_row['id'] ?? null;

            if ($ingrediente_id === null) {
                echo "Ingrediente não encontrado.";
            } else {
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
                        echo "<script>location.href='pages/cadastrarReceitas3.php?recipe_id=" . $recipe_id . "'</script>";
                    }
                }
            }
        }

        // Redireciona de volta para a página 
        header("Location: pages/cadastrarReceitas3.php?recipe_id=" . $recipe_id);
        exit();
    } else {
        echo "Erro: Sessão não iniciada ou ID da receita não definido.";
    }
}

$pdo = null;
