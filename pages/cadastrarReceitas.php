<?php
  require_once "header.php";
?> 


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>cadastrar receitas</title>
  <link rel="stylesheet" href="../css/cadastro.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>
<body>
  <form method="post" action="../cadastroReceitas.php" class="form" onsubmit="return validateForm()">
    <div class="form-group">
      <label for="nameRecipe" class="input-label">Nome recita:</label>
      <div class="input-container">
        <input type="text" id="name" name="name" required class="input-field" placeholder="ex: sopa de legumes">
      </div>
    </div>

    <div class="form-group">
    <label for="Ingredients" class="input-label">indredientes:</label>
      <div class="input-container">
        <input type="text" id="Ingredients" name="Ingredients" required class="input-field" placeholder="ex: sal, farinha">
      </div>
    </div>

    <div class="form-group">
    <label for="Subjects" class="input-label">materias:</label>
      <div class="input-container">
        <input type="text" id="Subjects" name="Subjects" required class="input-field" placeholder="ex: panela, faca">
      </div>
    </div>


    <div class="form-group">
      <label for="modePreparation" class="input-label">modo Preparo</label> 
      <div class="input-container">
        <input type="text" id="modePreparation" name="modePreparation" required class="input-field" placeholder="ex: modo preparo">
      </div>
    </div>

    <div class="form-group">
      <label for="price"class="input-label">Preço</label>
      <div class="input-container">
        <input type="text" id="price" name="praice" required class="input-field" placeholder="ex: R$ 35.00">
      </div> 
    </div>

    <div class="form-group">
      <label for="timePreparation" class="input-label">tempo Preparo</label>
      <div class="input-container">
        <input type="text" id="timePreparation" name="timePreparation" required class="input-field" placeholder="ex: 1h25min">
      </div>
    </div>
    
    <div class="form-group">
      <label for="comments"  class="input-label">obervações </label>
      <div class="input-container">
        <input type="text" id="comments"  name="input-label" required class="input-field" placeholder="ex: oberservações">
      </div>
    </div>

    <div class="buttonRecipe">
      <button type="submit">Cadastrar</button>
    </div>

</form>

  
</body>
</html>