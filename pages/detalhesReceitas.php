<?php
require_once "../config/conecta.php";
require_once "header.php";

// Verifica se o ID da receita foi passado via GET
if (isset($_GET['recipe_id'])) {
  $recipe_id = $_GET['recipe_id'];

  // Consulta os detalhes da receita no banco de dados
  $sql = "SELECT * FROM prato WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute([$recipe_id])) {
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $name = $row['nome'];
      $description = $row['modoPreparo']; // Ajustado para modoPreparo
      $cost = $row['custo'];
      $preparation_time = $row['tempoPreparo'];
      $observations = $row['observacoes'];
      $category_id = $row['categoria_id'];

      // Consulta o nome da categoria
      $sql_category = "SELECT nomeCategoria FROM categoria WHERE id = ?";
      $stmt_category = $pdo->prepare($sql_category);
      if ($stmt_category->execute([$category_id])) {
        if ($row_category = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
          $category_name = $row_category['nomeCategoria'];
        }
      } else {
        echo "Erro ao consultar a categoria.";
      }

      // Exibe os detalhes da receita
      echo "<h2>$name</h2>";
      echo "<p>Descrição: $description</p>";
      echo "<p>Custo: R$ $cost</p>";
      echo "<p>Tempo de Preparo: $preparation_time</p>";
      echo "<p>Observações: $observations</p>";
      echo "<p>Categoria: $category_name</p>";

?>
      <h2>Ingredientes cadastrados:</h2>
      <?php
      $sql = "select m.id, m.nomeMaterial, mp.prato_id from materiais_has_prato mp
        inner join materiais m on (m.id = mp.materiais_id)
        where mp.prato_id = ? order by m.nomeMaterial";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$recipe_id]);

      $materiais = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($materiais as $material) {
      ?>
        <p><?= $material->nomeMaterial ?></p>
      <?php
      }

      ?>
<?php


      echo '<a href="editarReceita.php?recipe_id=' . $recipe_id . '">Editar Receita</a>';
      echo '<a href="cadastrarReceitas2.php?recipe_id=' . $recipe_id . '">Alterar Materiais Receita</a>';
    } else {
      echo "Receita não encontrada.";
    }
  } else {
    echo "Erro ao consultar a receita.";
  }
} else {
  echo "ID da receita não foi especificado.";
}

$pdo = null;
