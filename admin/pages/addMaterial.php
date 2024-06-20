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
    <title>Adicionar Material</title>
    <link rel="stylesheet" href="../css/addMaterial.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>

<body>
    <?php
    require_once 'menu.php';
    ?>
    <h1>Adicionar Material</h1>
    <form method="post" action="../adionarMaterial.php" class="form" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="nomeMaterial" class="input-label">Nome do Material:</label>
            <div class="input-container">
                <input type="text" id="nomeMaterial" name="nomeMaterial" required class="input-field" placeholder="ex: Panela">
                <i class="fa-solid fa-utensils input-icon"></i>
            </div>
        </div>

        <div class="button">
            <button type="submit">Adicionar Material</button>
        </div>
    </form>

    <script>
        function validateForm() {
            var nomeMaterial = document.getElementById("nomeMaterial").value;
            if (nomeMaterial.trim() == "") {
                alert("Por favor, preencha o nome do material!");
                return false;
            }
        }
    </script>
</body>

</html>
