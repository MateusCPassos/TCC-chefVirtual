<?php
session_start();
require_once "../../config/conecta.php";

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["tipoUsuario"] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
    <link rel="stylesheet" href="../css/addCategoria.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
    <?php
    require_once 'menu.php';
    ?>
    <h1>Adicionar Categoria</h1>
    <form enctype="multipart/form-data" method="post" action="../adionarCategoria.php" class="form" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="nomeCategoria" class="input-label">Nome da Categoria:</label>
            <div class="input-container">
                <input type="text" id="nomeCategoria" name="nomeCategoria" required class="input-field" placeholder="ex: Sobremesas">
                <i class="fa-regular fa-folder input-icon"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="categoria-pic" class="input-label">Imagem da Categoria:</label>
            <div class="input-container">
                <input type="file" id="categoria-pic" name="categoria-pic" class="input-field" accept="image/*">
            </div>
        </div>

        <div class="button">
            <button type="submit">Adicionar Categoria</button>
        </div>
    </form>

    <script>
        function validateForm() {
            var nomeCategoria = document.getElementById("nomeCategoria").value;
            if (nomeCategoria.trim() == "") {
                alert("Por favor, preencha o nome da categoria!");
                return false;
            }
        }
    </script>
</body>

</html>