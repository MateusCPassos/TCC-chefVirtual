<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario_id = $_SESSION['id'];
    if(isset($_POST['recipe_id'])) {
        $recipe_id = $_POST['recipe_id'];

        // Verifica se a receita já está nos favoritos
        $sql_check = "SELECT * FROM favoritos WHERE usuario_id = ? AND prato_id = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$usuario_id, $recipe_id]);
        if ($stmt_check->rowCount() > 0) {
            echo "A receita já está nos seus favoritos.";
        } else {
            // Insere a receita nos favoritos
            $sql_insert = "INSERT INTO favoritos (usuario_id, prato_id) VALUES (?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$usuario_id, $recipe_id]);
            echo "Receita adicionada aos favoritos com sucesso.";
        }
    } else {
        echo "ID da receita não foi especificado.";
    }
} else {
    echo "Você precisa estar logado para adicionar aos favoritos.";
}
