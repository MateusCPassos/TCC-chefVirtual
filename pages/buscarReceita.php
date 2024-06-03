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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="../css/buscarReceita.css">
</head>

<body>
  <main>

    <form method="GET" action="buscarReceita.php" class="form-busca">
      <input type="text" name="search" placeholder="Buscar receita" required>
      <button type="submit">Buscar</button>
    </form>

    <?php
    if (isset($_GET['search'])) {
        $search = $_GET['search'];

        // Certifique-se de que a variável $pdo está definida
        if (!isset($pdo)) {
            require_once "../config/conecta.php";
        }

        // Define o número de itens por página
        $items_per_page = 9;

        // Pega o número da página atual da URL, se não existir, define como página 1
        $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $start = ($pagina_atual - 1) * $items_per_page;

        // Query para buscar o número total de receitas correspondentes
        $sql_total = "SELECT COUNT(*) FROM prato WHERE nome LIKE ?";
        $stmt_total = $pdo->prepare($sql_total);
        $stmt_total->execute(['%' . $search . '%']);
        $total_receitas = $stmt_total->fetchColumn();

        // Query para buscar as receitas
        $sql = "SELECT * FROM prato WHERE nome LIKE ? LIMIT ?, ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(2, $start, PDO::PARAM_INT);
        $stmt->bindValue(3, $items_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($receitas) > 0) {
            echo "<h2>Resultados da busca por: " . htmlspecialchars($search) . "</h2>";
            echo "<ul class='receitas-lista'>";
            foreach ($receitas as $receita) {
                echo "<li class='receita'>";
                if (!empty($receita['foto'])) {
                    echo "<img src='../" . htmlspecialchars($receita['foto']) . "' alt='Foto do Prato' class='foto-prato'>";
                }
                echo "<h3><a href='exibirReceita.php?recipe_id=" . htmlspecialchars($receita['id']) . "' class='receita-link'>" . htmlspecialchars($receita['nome']) . "</a></h3>";
                echo "<p class='info'>Tempo de Preparo: " . htmlspecialchars($receita['tempoPreparo']) . " minutos</p>";
                echo "<p class='info'>Custo: R$ " . number_format($receita['custo'], 2, ',', '.') . "</p>";
                ?>
                <a href="exibirReceita.php?recipe_id=<?php echo $receita['id']; ?>" class="button">Ver Receita</a>
                <?php
                echo "</li>";
            }
            echo "</ul>";

            // Paginação
            $total_paginas = ceil($total_receitas / $items_per_page);
            echo "<div class='pagination'>";
            if ($pagina_atual > 1) {
                echo "<a href='buscarReceita.php?search=" . urlencode($search) . "&pagina=" . ($pagina_atual - 1) . "'>&laquo; Anterior</a>";
            }
            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina_atual) {
                    echo "<span class='current'>$i</span>";
                } else {
                    echo "<a href='buscarReceita.php?search=" . urlencode($search) . "&pagina=$i'>$i</a>";
                }
            }
            if ($pagina_atual < $total_paginas) {
                echo "<a href='buscarReceita.php?search=" . urlencode($search) . "&pagina=" . ($pagina_atual + 1) . "'>Próxima &raquo;</a>";
            }
            echo "</div>";
        } else {
            echo "<p class='naoCadastrada'>Nenhuma receita encontrada com o termo '" . htmlspecialchars($search) . "'.</p>";
        }
    }
    ?>
  <?php require_once 'footer.php'; ?>
  </main>

</body>

</html>
