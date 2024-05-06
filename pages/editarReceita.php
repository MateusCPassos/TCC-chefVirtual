<?php
session_start();
require_once "../config/conecta.php";
require_once "header.php";

// Verifica se o ID da receita foi passado via GET
if (isset($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];

    // Consulta os detalhes da receita no banco de dados
    $sql = "SELECT * FROM prato WHERE id = ? AND usuario_id = ?";
    $stmt = $pdo->prepare($sql);
    $user_id = $_SESSION['id'];
    if ($stmt->execute([$recipe_id, $user_id])) {
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['nome'];
            $modePreparation = $row['modoPreparo']; // Ajustado para modoPreparo
            $cost = $row['custo'];
            $preparation_time = $row['tempoPreparo'];
            $observations = $row['observacoes'];
            $category_id = $row['categoria_id'];

            // Consulta todas as categorias disponíveis
            $sql_categories = "SELECT * FROM categoria";
            $stmt_categories = $pdo->query($sql_categories);
            $categories_options = '';
            while ($category = $stmt_categories->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($category_id == $category['id']) ? 'selected' : '';
                $categories_options .= "<option value='{$category['id']}' {$selected}>{$category['nomeCategoria']}</option>";
            }


?>
            <h2>Editar Receita</h2>
            <form action="../atualizarReceita.php" method="post">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                <div>
                    <label for="name">Nome da Receita:</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                </div>
                <div>
                    <label for="modePreparation">Modo de Preparo:</label>
                    <textarea id="modePreparation" name="modePreparation" rows="4" required><?php echo $modePreparation; ?></textarea>
                </div>
                <div>
                    <label for="cost">Custo:</label>
                    <input type="text" id="cost" name="cost" value="<?php echo $cost; ?>" required>
                </div>
                <div>
                    <label for="preparation_time">Tempo de Preparo:</label>
                    <input type="text" id="preparation_time" name="preparation_time" value="<?php echo $preparation_time; ?>" required>
                </div>
                <div>
                    <label for="observations">Observações:</label>
                    <input type="text" id="observations" name="observations" value="<?php echo $observations; ?>" required>
                </div>
                <div>
                    <label for="category">Categoria:</label>
                    <select id="category" name="category" required>
                        <?php echo $categories_options; ?>
                    </select>
                </div>
                <button type="submit">Atualizar Receita</button>
            </form>
<?php
        } else {
            echo "Você não tem permissão para editar esta receita.";
        }
    } else {
        echo "Erro ao consultar a receita.";
    }
} else {
    echo "ID da receita não foi especificado.";
}

$pdo = null;
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receita</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

</body>

</html>