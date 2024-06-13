<?php
require_once "../config/conecta.php";

// Verifica se o usuário está logado e se o ID da receita foi passado via GET
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_GET['recipe_id'])) {
        $usuario_id = $_SESSION['id'];
        $recipe_id = $_GET['recipe_id'];

        // Verifica se a receita já está nos favoritos do usuário
        $sql_check = "SELECT * FROM favoritos WHERE usuario_id = ? AND prato_id = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$usuario_id, $recipe_id]);

        if ($stmt_check->rowCount() == 0) {
            // Adiciona a receita aos favoritos
            $sql_insert = "INSERT INTO favoritos (usuario_id, prato_id) VALUES (?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            if ($stmt_insert->execute([$usuario_id, $recipe_id])) {
                echo "Receita adicionada aos favoritos com sucesso.";
            } else {
                echo "Erro ao adicionar a receita aos favoritos.";
            }
        } else {
            echo "A receita já está nos seus favoritos.";
        }
    }
}
