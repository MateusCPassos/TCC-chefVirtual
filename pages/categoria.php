<?php
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categorias</title>
  <link rel="stylesheet" href="../css/categorias.css">
  <link rel="shortcut icon" href="../assets/img/icone.png">

</head>

<body>
  <h2>Categorias</h2>
  <ul class="categorias-lista">
    <?php
    require_once "../config/conecta.php";

    $sql = "SELECT * FROM categoria";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categorias as $categoria) {
      echo '<li>';
      if (!empty($categoria['fotoCategoria'])) {
        echo '<img src="../' . htmlspecialchars($categoria['fotoCategoria']) . '" alt="' . htmlspecialchars($categoria['nomeCategoria']) . '" class="categoria-foto">';
      }
      echo '<a href="receitasPorCategoria.php?categoria_id=' . $categoria['id'] . '">' . htmlspecialchars($categoria['nomeCategoria']) . '</a>';
      echo '</li>';
    }

    $pdo = null;
    ?>
  </ul>
</body>

</html>
