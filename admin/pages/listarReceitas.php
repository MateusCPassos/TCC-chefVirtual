<?php
require_once "../../config/conecta.php";
require_once "menu.php";

// Defina a quantidade de receitas por página
$receitas_por_pagina = 9;

// Consulta para obter o número total de receitas
$sql_total_receitas = "SELECT COUNT(*) as total FROM prato";
$stmt_total = $pdo->prepare($sql_total_receitas);
$stmt_total->execute();
$total_receitas = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

// Calcula o total de páginas necessárias
$total_paginas = ceil($total_receitas / $receitas_por_pagina);

// Obtém o número da página atual
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcula o índice de início para a consulta
$indice_inicio = ($pagina_atual - 1) * $receitas_por_pagina;

// Consulta para obter as receitas paginadas
$sql_receitas = "SELECT * FROM prato LIMIT :indice_inicio, :receitas_por_pagina";
$stmt_receitas = $pdo->prepare($sql_receitas);
$stmt_receitas->bindParam(':indice_inicio', $indice_inicio, PDO::PARAM_INT);
$stmt_receitas->bindParam(':receitas_por_pagina', $receitas_por_pagina, PDO::PARAM_INT);
$stmt_receitas->execute();
$receitas_paginadas = $stmt_receitas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listar Receitas</title>
  <link rel="stylesheet" href="../css/listarReceitas.css">
</head>

<body>
  <main>
    <h2>Receitas receitas dos usuarios</h2>
    <?php if ($receitas_paginadas) : ?>
      <div class="receitas">
        <ul>
          <?php foreach ($receitas_paginadas as $receita) : ?>
            <li class="receita">
              <?php if (!empty($receita['foto'])) : ?>
                <img src="../../<?php echo htmlspecialchars($receita['foto']); ?>" alt="Foto do Prato" class="foto-prato">
              <?php endif; ?>
              <h3><a href="excluirReceitaAdmin.php?recipe_id=<?php echo $receita['id']; ?>"><?php echo htmlspecialchars($receita['nome']); ?></a></h3>
              <p class="tempo-preparo">Tempo de Preparo: <?php echo htmlspecialchars($receita['tempoPreparo']); ?> minutos</p>
              <p class="custo">Custo: R$ <?php echo number_format($receita['custo'], 2, ',', '.'); ?></p>
              <a href="excluirReceitaAdmin.php?recipe_id=<?php echo $receita['id']; ?>" class="button">Ver Receita</a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="pagination">
        <?php if ($pagina_atual > 1) : ?>
          <a href="listarReceitas.php?pagina=<?php echo ($pagina_atual - 1); ?>">&laquo; Anterior</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
          <?php if ($i == $pagina_atual) : ?>
            <span class="current"><?php echo $i; ?></span>
          <?php else : ?>
            <a href="listarReceitas.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($pagina_atual < $total_paginas) : ?>
          <a href="listarReceitas.php?pagina=<?php echo ($pagina_atual + 1); ?>">Próxima &raquo;</a>
        <?php endif; ?>
      </div>
    <?php else : ?>
      <div class="naoCadastrada">
        <div class="img">
          <img src="../assets/img/coracaoPartido.png" />
        </div>
        <div class="txtImg">
          <p>Não há receitas cadastradas.</p>
        </div>
      </div>
    <?php endif; ?>
  </main>
</body>

</html>
