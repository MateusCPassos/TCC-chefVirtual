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

    $recipe_id = $_GET["recipe_id"] ?? '';
    ?>
    <div class="campo">
    <h2>Cadastro de Ingredientes</h2>
    <form action="../cadastroReceitas3.php" method="post">
        <div class="form-group">
            <label for="ingrediente">Ingrediente:</label>
            <select name="ingrediente_id">
                <option value=""></option>
                <?php
                $sql = "SELECT * FROM indredientes ORDER BY NomeIndrediente"; // Corrigido o nome da tabela
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $ingredientes = $stmt->fetchAll(PDO::FETCH_OBJ);

                foreach ($ingredientes as $ingrediente) {
                ?>
                    <option value="<?= $ingrediente->id ?>"><?= $ingrediente->NomeIndrediente ?></option>
                <?php
                }

                ?>
            </select>
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