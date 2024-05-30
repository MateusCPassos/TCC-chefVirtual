<?php
session_start();
require_once "../config/conecta.php";

function logout()
{
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

require_once "header.php";

// Consulta para obter as receitas mais visitadas
$sql_receitas_mais_visitadas = "SELECT p.*, COUNT(l.prato_id) AS visitas
                                FROM prato p
                                LEFT JOIN log l ON p.id = l.prato_id
                                GROUP BY p.id
                                ORDER BY visitas DESC
                                LIMIT 5"; // Você pode ajustar o número de receitas recomendadas aqui
$stmt_receitas_mais_visitadas = $pdo->prepare($sql_receitas_mais_visitadas);
$stmt_receitas_mais_visitadas->execute();
$receitas_recomendadas = $stmt_receitas_mais_visitadas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>home</title>
  <link rel="stylesheet" href="../css/index.css">
</head>

<body>
  <main>
    <form method="GET" action="buscarReceita.php" class="form-busca">
      <input type="text" name="search" placeholder="Buscar receita" required>
      <button type="submit">Buscar</button>
    </form>

    <div class="recomendados">
            <h1>Recomendados</h1>
            <ul class="receitas-recomendadas">
                <?php foreach ($receitas_recomendadas as $receita) : ?>
                    <li><a href="exibirReceita.php?recipe_id=<?php echo $receita['id']; ?>"><?php echo htmlspecialchars($receita['nome']); ?> (<?php echo $receita['visitas']; ?> visitas)</a></li>
                <?php endforeach; ?>
            </ul>
        </div>






    <div class="categoria">
      <h1>categoria</h1>
      <ul class="categorias-lista">
        <?php
        require_once "../config/conecta.php";

        $sql = "SELECT * FROM categoria";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categorias as $categoria) {
          echo '<li><a href="receitasPorCategoria.php?categoria_id=' . $categoria['id'] . '">' . $categoria['nomeCategoria'] . '</a></li>';
        }

        $pdo = null;
        ?>
      </ul>
    </div>
  </main>
</body>

</html>