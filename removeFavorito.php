<?php
require_once "config/conecta.php";
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];
        $usuario_id = $_SESSION['id'];

        $sql_remove = "DELETE FROM favoritos WHERE usuario_id = ? AND prato_id = ?";
        $stmt_remove = $pdo->prepare($sql_remove);
        if ($stmt_remove->execute([$usuario_id, $recipe_id])) {
            header("Location: pages/index.php?recipe_id=$recipe_id");
            exit();
        } else {
            echo "Erro ao remover dos favoritos.";
        }
    }
} else {
    echo "Você precisa estar logado para remover dos favoritos.";
}
