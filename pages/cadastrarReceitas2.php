<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Materiais</title>
    <link rel="stylesheet" href="../css/cadastroReceitas2.css">
</head>

<body>
    <?php
    require_once "../config/conecta.php";
    require_once "header.php";

    $recipe_id = $_GET["recipe_id"] ?? '';
    ?>
    <div class="campo">
        <h2>Cadastro de Materiais</h2>
        <form action="../cadastroReceitas2.php" method="post">
            <div class="form-group">
                <input name="material_nome" list="materiais">
                <datalist id="materiais">
                    <?php
                    $sql = "SELECT * FROM materiais ORDER BY nomeMaterial";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $materiais = $stmt->fetchAll(PDO::FETCH_OBJ);

                    foreach ($materiais as $material) {
                        echo "<option value='{$material->nomeMaterial}'>";
                    }
                    ?>
                </datalist>
                <label for="material_nome">Material:</label>
            </div>
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <button type="submit">Salvar Materiais</button>
        </form>

        <h3>Materiais cadastrados:</h3>
        <?php
        $sql = "SELECT m.id, m.nomeMaterial, mp.prato_id FROM materiais_has_prato mp
                INNER JOIN materiais m ON (m.id = mp.materiais_id)
                WHERE mp.prato_id = ? ORDER BY m.nomeMaterial";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$recipe_id]);

        $materiais = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($materiais as $material) {
            ?>
            <p><?= $material->nomeMaterial ?></p>
        <?php
        }
        ?>
        <a href="cadastrarReceitas3.php?recipe_id=<?php echo $recipe_id; ?>" class="btn-continuar">Continuar</a>
    </div>
</body>

</html>
