<?php
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categorias</title>
  <link rel="stylesheet" href="../css/categorias.css">
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
      echo '<li><a href="receitasPorCategoria.php?categoria_id=' . $categoria['id'] . '">' . $categoria['nomeCategoria'] . '</a></li>';
    }

    $pdo = null;
    ?>
  </ul>

</body>

</html>