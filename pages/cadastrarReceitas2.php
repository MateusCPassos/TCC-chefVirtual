<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Materiais</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    require_once "../config/conecta.php";
    require_once "header.php";

    $recipe_id = $_GET["recipe_id"] ?? '';
    ?>
    <h2>Cadastro de Materiais</h2>
    <form action="../cadastroReceitas2.php" method="post">
        <div class="form-group">
            <label for="material">Material:</label>
            <select name="material_id">
                <option value=""></option>
                <?php
                $sql = "select * from materiais order by nomeMaterial";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $materiais = $stmt->fetchAll(PDO::FETCH_OBJ);

                foreach ($materiais as $material) {
                ?>
                    <option value="<?= $material->id ?>"><?= $material->nomeMaterial ?></option>
                <?php
                }

                ?>
            </select>
        </div>
        <input type="hidden" name="recipe_id" value="<?php echo $_GET['recipe_id']; ?>">
        <button type="submit">Salvar Materiais</button>
    </form>

    <h2>Ingredientes cadastrados:</h2>
    <?php
    $sql = "select m.id, m.nomeMaterial, mp.prato_id from materiais_has_prato mp
        inner join materiais m on (m.id = mp.materiais_id)
        where mp.prato_id = ? order by m.nomeMaterial";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$recipe_id]);

    $materiais = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($materiais as $material) {
    ?>
        <p><?= $material->nomeMaterial ?></p>
    <?php
    }

    ?>
</body>

</html>