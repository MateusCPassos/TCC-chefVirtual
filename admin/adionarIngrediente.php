<?php
session_start();
require_once "../config/conecta.php";

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["tipoUsuario"] !== 'admin') {
    header("Location: pages/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeIngrediente = isset($_POST["nomeIngrediente"]) ? trim($_POST["nomeIngrediente"]) : '';

    // Verifica se o campo obrigatório está preenchido
    if (empty($nomeIngrediente)) {
        echo "Por favor, preencha o nome do ingrediente.";
    } else {
        // Insere o novo ingrediente
        $sql = "INSERT INTO indredientes (NomeIndrediente) VALUES (?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nomeIngrediente])) {
            echo "<script>alert('Ingrediente adicionado com sucesso!'); window.location.href='pages/addIngrediente.php';</script>";
        } else {
            echo "Erro ao adicionar ingrediente.";
        }
        $stmt->closeCursor();
    }
}

$pdo = null;
?>
