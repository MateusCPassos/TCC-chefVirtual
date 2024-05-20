<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>detalhes receita</title>
  <link rel="stylesheet" href="../css/exibirReceita.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
  <div class="container">
    <?php
    require_once "../config/conecta.php";
    session_start(); // Certifique-se de iniciar a sessão
    require_once "header.php";
    require_once "addFavoritos.php";


    // Verifica se o ID da receita foi passado via GET
    if (isset($_GET['recipe_id'])) {
      $recipe_id = $_GET['recipe_id'];

      // Verifica se o usuário está logado
      if (isset($_SESSION['id'])) {
        $usuario_id = $_SESSION['id']; // Utilize o índice correto da sessão

        // Consulta os detalhes da receita no banco de dados
        $sql = "SELECT * FROM prato WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$recipe_id])) {
          if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['nome'];
            $description = $row['modoPreparo'];
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
              echo "<p>Erro ao consultar a categoria.</p>";
            }

            // Exibe os detalhes da receita
            echo "<h2>$name <a href='addFavorito.php?recipe_id=$recipe_id' class='heart'><i class='fa-regular fa-heart'></i></a></h2>";
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
          } else {
            echo "<p>Receita não encontrada.</p>";
          }
        } else {
          echo "<p>Erro ao consultar a receita.</p>";
        }
      } else {
        echo "<p>Você precisa estar logado para adicionar aos favoritos.</p>";
      }
    } else {
      echo "<p>ID da receita não foi especificado.</p>";
    }

    $pdo = null;
    ?>
  </div>
  <?php
  require_once 'footer.php';
