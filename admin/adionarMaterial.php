<?php
session_start();
require_once "../config/conecta.php";

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["tipoUsuario"] !== 'admin') {
    header("Location: pages/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeMaterial = isset($_POST["nomeMaterial"]) ? trim($_POST["nomeMaterial"]) : '';

    // Verifica se o campo obrigatório está preenchido
    if (empty($nomeMaterial)) {
        echo "Por favor, preencha o nome do material.";
    } else {
        // Insere o novo material
        $sql = "INSERT INTO materiais (nomeMaterial) VALUES (?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nomeMaterial])) {
            echo "<script>alert('Material adicionado com sucesso!'); window.location.href='pages/addMaterial.php';</script>";
        } else {
            echo "Erro ao adicionar material.";
        }
        $stmt->closeCursor();
    }
}

$pdo = null;
?>
