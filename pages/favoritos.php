<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>favoritos</title>
  <link rel="stylesheet" href="../css/favoritos.css">
<body>
  
</body>
</html>


<?php
require_once "../config/conecta.php";
require_once "header.php";
require_once "../addFavoritos.php";

if (isset($_SESSION['id'])) {
    $usuario_id = $_SESSION['id'];
    $sql_favoritos = "SELECT p.id, p.nome, p.modoPreparo, p.custo, p.tempoPreparo, p.observacoes, c.nomeCategoria
                      FROM favoritos f
                      INNER JOIN prato p ON f.prato_id = p.id
                      INNER JOIN categoria c ON p.categoria_id = c.id
                      WHERE f.usuario_id = ?";
    $stmt_favoritos = $pdo->prepare($sql_favoritos);
    if ($stmt_favoritos) {
        $stmt_favoritos->execute([$usuario_id]);
        $favoritos = $stmt_favoritos->fetchAll(PDO::FETCH_ASSOC);

        if (count($favoritos) > 0) {
            echo "<h2>Minhas Receitas Favoritas</h2>";
            echo "<ul class='receitas-lista'>";
            foreach ($favoritos as $favorito) {
                echo "<li class='receita'>";
                echo "<a href='exibirReceita.php?recipe_id=" . htmlspecialchars($favorito['id']) . "' class='receita-link'>" . htmlspecialchars($favorito['nome']) . "</a>";
                echo "<p class='info'>Tempo de Preparo: " . htmlspecialchars($favorito['tempoPreparo']) . " minutos</p>";
                echo "<p class='info'>Custo: R$ " . number_format($favorito['custo'], 2, ',', '.') . "</p>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='naoCadastrada'>Você ainda não tem receitas favoritadas.</p>";
        }
    } else {
        echo "<p class='naoCadastrada'>Erro ao preparar a consulta.</p>";
    }
} else {
    echo "<p class='naoCadastrada'>Você precisa estar logado para ver seus favoritos.</p>";
}

$pdo = null;
require_once 'footer.php';
?>
