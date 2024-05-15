<?php
require_once "../categoriaReceitas.php";
require_once "header.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receitas da Categoria: <?php echo $categoria_nome; ?></title>
  <link rel="stylesheet" href="../css/receitas_por_categoria.css">
</head>

<body>
  <main>
    <h2>Receitas da Categoria: <?php echo $categoria_nome; ?></h2>
    <?php if ($receitas) : ?>
      <ul class="receitas-lista">
        <!-- PHP para listar as receitas da categoria -->
        <?php foreach ($receitas as $receita) : ?>
          <li><a href="exibirReceita.php?recipe_id=<?php echo $receita['id']; ?>"><?php echo $receita['nome']; ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <img src="../assets/img/coracaoPartido.png" />
      <p>Não há receitas cadastradas para esta categoria.</p>
    <?php endif; ?>
  </main>
</body>

</html>