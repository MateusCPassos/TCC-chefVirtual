<?php
require_once "config/conecta.php";

// obter o nome da categoria com base no ID
function obterNomeCategoria($pdo, $categoria_id)
{
    $sql = "SELECT nomeCategoria FROM categoria WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoria_id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    return $categoria['nomeCategoria'];
}

// obter as receitas vinculadas a uma categoria
function obterReceitasPorCategoria($pdo, $categoria_id)
{
    $sql = "SELECT * FROM prato WHERE categoria_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoria_id]);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $receitas;
}

// Verifica se o parâmetro categoria_id foi passado
if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];
    $categoria_nome = obterNomeCategoria($pdo, $categoria_id);
    $receitas = obterReceitasPorCategoria($pdo, $categoria_id);
} else {
    //redireciona para a página de categorias
    header("Location: categorias.php");
    exit;
}

$pdo = null;
