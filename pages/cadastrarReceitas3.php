<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Ingredientes</title>
    <link rel="stylesheet" href="../css/cadastroReceitas3.css">
</head>

<body>
    <?php
    require_once "../config/conecta.php";
    require_once "header.php";

    // Verifica se o usuário está autenticado
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $recipe_id = $_GET["recipe_id"] ?? '';

    // Consulta se a receita pertence ao usuário
    $sql_check_recipe = "SELECT id FROM prato WHERE id = ? AND usuario_id = ?";
    $stmt_check_recipe = $pdo->prepare($sql_check_recipe);
    $stmt_check_recipe->execute([$recipe_id, $_SESSION['id']]);
    $recipe_exists = $stmt_check_recipe->fetchColumn();

    // Verifica se a receita existe e pertence ao usuário
    if (!$recipe_id || !$recipe_exists) {
        echo "receita não pertence ao usuário.";
        exit;
    }
    ?>
    <div class="campo">
        <h2>Cadastro de Ingredientes</h2>
        <form action="../cadastroReceitas3.php" method="post">
            <div class="form-group">
                <input name="ingredientes_id" list="ingredientes">
                <datalist id="ingredientes">
                    <?php
                    $sql = "SELECT * FROM indredientes ORDER BY NomeIndrediente";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $ingredientes = $stmt->fetchAll(PDO::FETCH_OBJ);

                    foreach ($ingredientes as $ingrediente) {
                        echo "<option value='{$ingrediente->id} - {$ingrediente->NomeIndrediente}'>";
                    }
                    ?>

                </datalist>

                <label for="ingrediente">Ingrediente:</label>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="text" name="quantidade">
            </div>
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <button type="submit">Salvar Ingrediente</button>
        </form>

        <h3>Ingredientes cadastrados:</h3>
        <?php
        $sql = "SELECT i.id, i.NomeIndrediente, pi.prato_id, pi.quantidade FROM prato_has_indredientes pi
            INNER JOIN indredientes i ON (i.id = pi.indredientes_id) 
            WHERE pi.prato_id = ? ORDER BY i.NomeIndrediente";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$recipe_id]);

        $ingredientes = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($ingredientes as $ingrediente) {
        ?>
            <p><?= $ingrediente->NomeIndrediente ?> - <?= $ingrediente->quantidade ?></p>
        <?php
        }
        ?>
        <a href="detalhesReceitas.php?recipe_id=<?php echo $recipe_id; ?>" class="btn-continuar">Finalizar</a>
    </div>
</body>

</html>
