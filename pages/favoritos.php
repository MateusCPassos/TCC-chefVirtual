<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos</title>
    <link rel="stylesheet" href="../css/favoritos.css">
</head>

<body>
    <?php
    require_once "../config/conecta.php";
    session_start();
    require_once "header.php";
    require_once "../addFavoritos.php";

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $usuario_id = $_SESSION['id'];

        // Define o número de itens por página
        $items_per_page = 9;

        // Pega o número da página atual da URL, se não existir, define como página 1
        $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $start = ($pagina_atual - 1) * $items_per_page;

        // Busca o número total de favoritos
        $sql_total = "SELECT COUNT(*) FROM favoritos WHERE usuario_id = ?";
        $stmt_total = $pdo->prepare($sql_total);
        $stmt_total->execute([$usuario_id]);
        $total_favoritos = $stmt_total->fetchColumn();

        // Busca os favoritos para a página atual
        $sql_favoritos = "SELECT p.id, p.nome, p.foto, p.modoPreparo, p.custo, p.tempoPreparo, p.observacoes, c.nomeCategoria
                        FROM favoritos f
                        INNER JOIN prato p ON f.prato_id = p.id
                        INNER JOIN categoria c ON p.categoria_id = c.id
                        WHERE f.usuario_id = ?
                        LIMIT ?, ?";
        $stmt_favoritos = $pdo->prepare($sql_favoritos);
        $stmt_favoritos->bindParam(1, $usuario_id, PDO::PARAM_INT);
        $stmt_favoritos->bindParam(2, $start, PDO::PARAM_INT);
        $stmt_favoritos->bindParam(3, $items_per_page, PDO::PARAM_INT);

        if ($stmt_favoritos) {
            $stmt_favoritos->execute();
            $favoritos = $stmt_favoritos->fetchAll(PDO::FETCH_ASSOC);

            if (count($favoritos) > 0) {
                echo "<h2>Minhas Receitas Favoritas</h2>";
                echo "<ul class='receitas-lista'>";
                foreach ($favoritos as $favorito) {
                    echo "<li class='receita'>";
                    if (!empty($favorito['foto'])) {
                        echo "<img src='../" . htmlspecialchars($favorito['foto']) . "' alt='Foto do Prato' class='foto-prato'>";
                    }
                    echo "<h3><a href='exibirReceita.php?recipe_id=" . htmlspecialchars($favorito['id']) . "' class='receita-link'>" . htmlspecialchars($favorito['nome']) . "</a></h3>";
                    echo "<p class='info'>Tempo de Preparo: " . htmlspecialchars($favorito['tempoPreparo']) . " minutos</p>";
                    echo "<p class='info'>Custo: R$ " . number_format($favorito['custo'], 2, ',', '.') . "</p>";
                    echo "</li>";
                }
                echo "</ul>";

                // Paginação
                $total_paginas = ceil($total_favoritos / $items_per_page);
                echo "<div class='pagination'>";
                if ($pagina_atual > 1) {
                    echo "<a href='favoritos.php?pagina=" . ($pagina_atual - 1) . "'>&laquo; Anterior</a>";
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina_atual) {
                        echo "<span class='current'>$i</span>";
                    } else {
                        echo "<a href='favoritos.php?pagina=$i'>$i</a>";
                    }
                }
                if ($pagina_atual < $total_paginas) {
                    echo "<a href='favoritos.php?pagina=" . ($pagina_atual + 1) . "'>Próxima &raquo;</a>";
                }
                echo "</div>";
            } else {
    ?>
                <div class="naoCadastrada">
                    <div class="img">
                        <img src="../assets/img/coracaoPartido.png" />
                    </div>
                    <div class="txtImg">
                        <p>Não há receitas adicionadas ao favorito.</p>
                    </div>
                </div>
    <?php
            }
        } else {
            echo "<p class='naoCadastrada'>Erro ao preparar a consulta.</p>";
        }
    } else {
        echo "<p class='naoCadastrada'>Você precisa estar logado para ver suas receitas adicionadas ao favorito.</p>";
    }

    $pdo = null;
    require_once 'footer.php';
    ?>
</body>

</html>
