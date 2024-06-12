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
    echo "ID da receita não foi especificado ou receita não pertence ao usuário.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Materiais</title>
    <link rel="stylesheet" href="../css/cadastroReceitas2.css">
    <link rel="shortcut icon" href="../assets/img/icone.png">

</head>

<body>
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
            <button type="submit" class="btn-salvar-materiais">Salvar Materiais</button>
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
            <div class="material-item">
                <p><?= $material->nomeMaterial ?></p>
                <form action="../cadastroReceitas2.php" method="post" style="display:inline;">
                    <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                    <input type="hidden" name="remove_material_id" value="<?= $material->id ?>">
                    <button type="submit">Remover</button>
                </form>
            </div>
        <?php
        }
        ?>
        <a href="cadastrarReceitas3.php?recipe_id=<?php echo $recipe_id; ?>" class="btn-continuar">Continuar</a>
    </div>
</body>

</html>