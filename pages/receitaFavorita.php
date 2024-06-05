<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receita Favorita</title>
  <link rel="stylesheet" href="../css/receitaFavorita.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
  <?php
  require_once "../config/conecta.php";
  session_start();
  require_once "header.php";
  ?>
  <div class="container">
    <?php
    if (isset($_GET['recipe_id'])) {
      $recipe_id = $_GET['recipe_id'];

      $sql_insert_log = "INSERT INTO log (prato_id, data) VALUES (?, NOW())";
      $stmt_insert_log = $pdo->prepare($sql_insert_log);
      $stmt_insert_log->execute([$recipe_id]);

      $sql = "SELECT p.*, u.nome AS usuario_nome, u.fotoUsuariocol AS usuario_foto FROM prato p
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
          $usuario_foto = $row['usuario_foto'];

          $sql_category = "SELECT nomeCategoria FROM categoria WHERE id = ?";
          $stmt_category = $pdo->prepare($sql_category);
          if ($stmt_category->execute([$category_id])) {
            if ($row_category = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
              $category_name = $row_category['nomeCategoria'];
            }
          } else {
            echo "<p>Erro ao consultar a categoria.</p>";
          }

          $is_favorite = false;
          if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $usuario_id = $_SESSION['id'];
            $sql_favorite = "SELECT * FROM favoritos WHERE usuario_id = ? AND prato_id = ?";
            $stmt_favorite = $pdo->prepare($sql_favorite);
            $stmt_favorite->execute([$usuario_id, $recipe_id]);
            if ($stmt_favorite->fetch()) {
              $is_favorite = true;
            }
          }

          echo "<p>Receita criada por: $usuario_nome</p>";
    ?>
          <hr>
          <div class="header">
            <?php
            if (!empty($foto)) {
              echo "<div class='foto'><img src='../" . htmlspecialchars($foto) . "' alt='Foto do Prato'></div>";
            }

            echo "<div class='nome'><h2>$name";
            if ($is_favorite) {
              echo " <a href='removeFavorito.php?recipe_id=$recipe_id' class='heart'><i class='fas fa-heart' style='color:red;'></i></a>";
            } else {
              echo " <a href='addFavorito.php?recipe_id=$recipe_id' class='heart'><i class='fa-regular fa-heart'></i></a>";
            }
            echo "</h2></div>";
            ?>
          </div>

          <div class="section">
            <h2>Ingredientes:</h2>
            <?php
            $sql_ingredientes = "SELECT i.id, i.NomeIndrediente, pi.quantidade FROM prato_has_indredientes pi
                               INNER JOIN indredientes i ON (i.id = pi.indredientes_id)
                               WHERE pi.prato_id = ?";
            $stmt_ingredientes = $pdo->prepare($sql_ingredientes);
            $stmt_ingredientes->execute([$recipe_id]);
            $ingredientes = $stmt_ingredientes->fetchAll(PDO::FETCH_OBJ);
            foreach ($ingredientes as $ingrediente) {
              echo "<p>" . htmlspecialchars($ingrediente->NomeIndrediente) . " - " . htmlspecialchars($ingrediente->quantidade) . "</p>";
            }
            ?>
          </div>

          <div class="section">
            <h2>Modo Preparo:</h2>
            <p><?php echo $description; ?></p>
          </div>

          <div class="section">
            <h2>Materiais cadastrados:</h2>
            <?php
            $sql_materiais = "SELECT m.id, m.nomeMaterial, mp.prato_id FROM materiais_has_prato mp
                              INNER JOIN materiais m ON (m.id = mp.materiais_id)
                              WHERE mp.prato_id = ? ORDER BY m.nomeMaterial";
            $stmt_materiais = $pdo->prepare($sql_materiais);
            $stmt_materiais->execute([$recipe_id]);
            $materiais = $stmt_materiais->fetchAll(PDO::FETCH_OBJ);
            foreach ($materiais as $material) {
              echo "<p>" . htmlspecialchars($material->nomeMaterial) . "</p>";
            }
            ?>
          </div>

          <div class="details-grid">
            <p>Custo: R$ <?php echo $cost; ?></p>
            <p>Tempo de Preparo: <?php echo $preparation_time; ?> minutos</p>
            <p>Categoria: <?php echo $category_name; ?></p>
          </div>

          <div class="observacoes">
            <p>Observações: <?php echo $observations; ?></p>
          </div>
          <hr>
          <?php
          if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
          ?>
            <h2>Adicionar Avaliação</h2>
            <form action="" method="post">
              <textarea name="avaliacao" rows="4" cols="50" placeholder="Adicione sua avaliação aqui..."></textarea><br>
              <input type="submit" value="Enviar Avaliação">
            </form>
    <?php
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
          echo "<h2>Avaliações:</h2>";
          $sql_avaliacoes = "SELECT a.*, u.nome AS usuario_nome, u.fotoUsuariocol AS usuario_foto FROM avaliacao a
              INNER JOIN usuario u ON a.usuario_id = u.id
              WHERE a.prato_id = ? ORDER BY a.data DESC";
          $stmt_avaliacoes = $pdo->prepare($sql_avaliacoes);
          $stmt_avaliacoes->execute([$recipe_id]);
          $avaliacoes = $stmt_avaliacoes->fetchAll(PDO::FETCH_ASSOC);
          foreach ($avaliacoes as $avaliacao) {
            echo "<div class='comentario'><img src='../" . htmlspecialchars($avaliacao['usuario_foto']) . "' alt='Foto do Usuário'><strong>" . htmlspecialchars($avaliacao['usuario_nome']) . "</strong> (" . htmlspecialchars($avaliacao['data']) . "): " . htmlspecialchars($avaliacao['avaliacaoDoPrato']) . "</div>";
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
</body>

</html>