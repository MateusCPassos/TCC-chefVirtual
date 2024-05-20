<?php
require_once "../categoriaReceitas.php";
require_once "header.php";

// Defina a quantidade de receitas por página
$receitas_por_pagina = 9;

// Obtém o número total de receitas
$total_receitas = count($receitas);

// Calcula o total de páginas necessárias
$total_paginas = ceil($total_receitas / $receitas_por_pagina);

// Obtém o número da página atual
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcula o índice de início para a consulta
$indice_inicio = ($pagina_atual - 1) * $receitas_por_pagina;

// Obtém as receitas para a página atual
$receitas_paginadas = array_slice($receitas, $indice_inicio, $receitas_por_pagina);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receitas da Categoria: <?php echo $categoria_nome; ?></title>
  <link rel="stylesheet" href="../css/receitasPorCategoria.css">
</head>

<body>
  <main>
    <h2>Receitas da Categoria: <?php echo $categoria_nome; ?></h2>
    <?php if ($receitas_paginadas) : ?>
      <ul class="receitas-lista">
        <?php foreach ($receitas_paginadas as $receita) : ?>
          <li>
            <a href="exibirReceita.php?recipe_id=<?php echo $receita['id']; ?>">
              <?php echo $receita['nome']; ?>
            </a>
            <p class="info">Tempo de Preparo: <?php echo $receita['tempoPreparo']; ?> minutos</p>
            <p class="info">Custo: R$ <?php echo number_format($receita['custo'], 2, ',', '.'); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="pagination">
        <?php if ($pagina_atual > 1) : ?>
          <a href="receitasPorCategoria.php?categoria_id=<?php echo $categoria_id; ?>&pagina=<?php echo ($pagina_atual - 1); ?>">&laquo; Anterior</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
          <?php if ($i == $pagina_atual) : ?>
            <span class="current"><?php echo $i; ?></span>
          <?php else : ?>
            <a href="receitasPorCategoria.php?categoria_id=<?php echo $categoria_id; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($pagina_atual < $total_paginas) : ?>
          <a href="receitasPorCategoria.php?categoria_id=<?php echo $categoria_id; ?>&pagina=<?php echo ($pagina_atual + 1); ?>">Próxima &raquo;</a>
        <?php endif; ?>
      </div>

    <?php else : ?>
      <div class="naoCadastrada">
        <div class="img">
          <img src="../assets/img/coracaoPartido.png" />
        </div>
        <div class="txtImg">
          <p>Não há receitas cadastradas nesta categoria.</p>
        </div>
      </div>
    <?php endif; ?>
  </main>
</body>

</html>