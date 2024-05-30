<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes da Receita</title>
  <link rel="stylesheet" href="../css/exibirReceita.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
  <div class="container">
    <?php
    require_once "../config/conecta.php";
    require_once "header.php";

    // Verifica se o ID da receita foi passado via GET
    if (isset($_GET['recipe_id'])) {
      $recipe_id = $_GET['recipe_id'];

      // Consulta os detalhes da receita no banco de dados
      $sql = "SELECT p.*, u.nome AS usuario_nome FROM prato p
              INNER JOIN usuario u ON p.usuario_id = u.id
              WHERE p.id = ?";
      $stmt = $pdo->prepare($sql);
      if ($stmt->execute([$recipe_id])) {
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $name = $row['nome'];
          $description = $row['modoPreparo'];
          $cost = $row['custo'];
          $preparation_time = $row['tempoPreparo'];
          $observations = $row['observacoes'];
          $category_id = $row['categoria_id'];
          $foto = $row['foto'];
          $usuario_nome = $row['usuario_nome'];

          // Consulta o nome da categoria
          $sql_category = "SELECT nomeCategoria FROM categoria WHERE id = ?";
          $stmt_category = $pdo->prepare($sql_category);
          if ($stmt_category->execute([$category_id])) {
            if ($row_category = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
              $category_name = $row_category['nomeCategoria'];
            }
          } else {
            echo "<p>Erro ao consultar a categoria.</p>";
          }

          // Exibe os detalhes da receita
          echo "<h2>$name <a href='favoritos.php?recipe_id=$recipe_id' class='heart'><i class='fa-regular fa-heart'></i></a></h2>";
          echo "<p>Receita criada por: $usuario_nome</p>";
          if (!empty($foto)) {
            echo "<img src='../$foto' alt='Foto do Prato' style='max-width: 200px;'>";
          }
          echo "<p>Modo Preparo: $description</p>";
          echo "<p>Custo: R$ $cost</p>";
          echo "<p>Tempo de Preparo: $preparation_time</p>";
          echo "<p>Observações: $observations</p>";
          echo "<p>Categoria: $category_name</p>";

          echo "<h2>Materiais cadastrados:</h2>";
          $sql_materiais = "SELECT m.id, m.nomeMaterial, mp.prato_id FROM materiais_has_prato mp
                              INNER JOIN materiais m ON (m.id = mp.materiais_id)
                              WHERE mp.prato_id = ? ORDER BY m.nomeMaterial";
          $stmt_materiais = $pdo->prepare($sql_materiais);
          $stmt_materiais->execute([$recipe_id]);
          $materiais = $stmt_materiais->fetchAll(PDO::FETCH_OBJ);
          foreach ($materiais as $material) {
            echo "<p>" . htmlspecialchars($material->nomeMaterial) . "</p>";
          }

          echo "<h2>Ingredientes cadastrados:</h2>";
          $sql_ingredientes = "SELECT i.id, i.NomeIndrediente, pi.quantidade FROM prato_has_indredientes pi
                                 INNER JOIN indredientes i ON (i.id = pi.indredientes_id)
                                 WHERE pi.prato_id = ?";
          $stmt_ingredientes = $pdo->prepare($sql_ingredientes);
          $stmt_ingredientes->execute([$recipe_id]);
          $ingredientes = $stmt_ingredientes->fetchAll(PDO::FETCH_OBJ);
          foreach ($ingredientes as $ingrediente) {
            echo "<p>" . htmlspecialchars($ingrediente->NomeIndrediente) . " - " . htmlspecialchars($ingrediente->quantidade) . "</p>";
          }

          // Formulário para adicionar avaliação
          if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            echo "<h2>Adicionar Avaliação</h2>";
            echo "<form action='' method='post'>
                    <textarea name='avaliacao' rows='4' cols='50' placeholder='Adicione sua avaliação aqui...'></textarea><br>
                    <input type='submit' value='Enviar Avaliação'>
                  </form>";

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['avaliacao'])) {
              $avaliacao = $_POST['avaliacao'];
              $usuario_id = $_SESSION['id'];
              $data = date('Y-m-d');

              $sql_add_avaliacao = "INSERT INTO avaliacao (avaliacaoDoPrato, data, usuario_id, prato_id) VALUES (?, ?, ?, ?)";
              $stmt_add_avaliacao = $pdo->prepare($sql_add_avaliacao);
              if ($stmt_add_avaliacao->execute([$avaliacao, $data, $usuario_id, $recipe_id])) {
                echo "<p>Avaliação adicionada com sucesso!</p>";
              } else {
                echo "<p>Erro ao adicionar avaliação.</p>";
              }
            }
          } else {
            echo "<p>Você precisa estar logado para adicionar avaliações.</p>";
          }

          // Exibe as avaliações
          echo "<h2>Avaliações:</h2>";
          $sql_avaliacoes = "SELECT a.*, u.nome AS usuario_nome FROM avaliacao a
                             INNER JOIN usuario u ON a.usuario_id = u.id
                             WHERE a.prato_id = ? ORDER BY a.data DESC";
          $stmt_avaliacoes = $pdo->prepare($sql_avaliacoes);
          $stmt_avaliacoes->execute([$recipe_id]);
          $avaliacoes = $stmt_avaliacoes->fetchAll(PDO::FETCH_ASSOC);
          foreach ($avaliacoes as $avaliacao) {
            echo "<p><strong>" . htmlspecialchars($avaliacao['usuario_nome']) . "</strong> (" . htmlspecialchars($avaliacao['data']) . "): " . htmlspecialchars($avaliacao['avaliacaoDoPrato']) . "</p>";
          }
        } else {
          echo "<p>Receita não encontrada.</p>";
        }
      } else {
        echo "<p>Erro ao consultar a receita.</p>";
      }
    } else {
      echo "<p>ID da receita não foi especificado.</p>";
    }

    $pdo = null;
    ?>
  </div>
  <?php
  require_once 'footer.php';
  ?>
</body>

</html>