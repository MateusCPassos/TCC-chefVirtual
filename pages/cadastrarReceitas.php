<?php
session_start();
require_once "../config/conecta.php";

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
  header("Location: login.php");
  exit;
}

$sql = "SELECT * FROM categoria";
$stmt = $pdo->query($sql);

$options = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $options .= "<option value=\"{$row['id']}\">{$row['nomeCategoria']}</option>";
}

$pdo = null;
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Receitas</title>
  <link rel="stylesheet" href="../css/cadastroReceitas.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
  <form enctype="multipart/form-data" method="post" action="../cadastroReceitas.php" class="form" onsubmit="return validateForm()">
  <div class="form-group">
      <label for="arquivo" class="input-label">Imagem do Prato:</label>
      <div class="input-container">
        <input type="file" id="arquivo" name="arquivo" class="input-field" required>
      </div>
    </div>
    
    <div class="form-group">
      <label for="nameRecipe" class="input-label">Nome da Receita:</label>
      <div class="input-container">
        <input type="text" id="nameRecipe" name="nameRecipe" required class="input-field" placeholder="ex: Sopa de Legumes">
      </div>
    </div>

    <div class="form-group">
      <label for="modePreparation" class="input-label">Modo de Preparo:</label>
      <div class="input-container">
        <textarea id="modePreparation" name="modePreparation" required class="input-field" rows="6" placeholder="ex: Modo de preparo"></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="price" class="input-label">Preço:</label>
      <div class="input-container">
        <input type="text" id="price" name="price" required class="input-field" placeholder="ex: R$ 35.00">
      </div>
    </div>

    <div class="form-group">
      <label for="timePreparation" class="input-label">Tempo de Preparo em minutos:</label>
      <div class="input-container">
        <input type="text" id="timePreparation" name="timePreparation" required class="input-field" placeholder="ex: 120">
      </div>
    </div>

    <div class="form-group">
      <label for="comments" class="input-label">Observações:</label>
      <div class="input-container">
        <textarea type="text" id="comments" name="comments" required class="input-field" placeholder="ex: Observações"></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="category" class="input-label">Categoria:</label>
      <div class="input-container">
        <select id="category" name="category" required>
          <option value="">Selecione a categoria</option>
          <?php echo $options; ?>
        </select>
      </div>
    </div>

    

    <div class="buttonRecipe">
      <button type="submit">Cadastrar</button>
    </div>
  </form>
</body>

</html>
