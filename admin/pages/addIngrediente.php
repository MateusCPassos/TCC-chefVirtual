<?php
session_start();
require_once "../../config/conecta.php";

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["tipoUsuario"] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Ingrediente</title>
    <link rel="stylesheet" href="../css/addIngrediente.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
    <?php
    require_once 'menu.php';
    ?>
    <h1>Adicionar Ingrediente</h1>
    <form method="post" action="../adionarIngrediente.php" class="form" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="nomeIngrediente" class="input-label">Nome do Ingrediente:</label>
            <div class="input-container">
                <input type="text" id="nomeIngrediente" name="nomeIngrediente" required class="input-field" placeholder="ex: Açúcar">
                <i class="fa-solid fa-carrot input-icon"></i>
            </div>
        </div>

        <div class="button">
            <button type="submit">Adicionar Ingrediente</button>
        </div>
    </form>

    <script>
        function validateForm() {
            var nomeIngrediente = document.getElementById("nomeIngrediente").value;
            if (nomeIngrediente.trim() == "") {
                alert("Por favor, preencha o nome do ingrediente!");
                return false;
            }
        }
    </script>
</body>

</html>
