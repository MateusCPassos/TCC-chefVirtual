<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receita</title>
    <link rel="stylesheet" href="../css/editarReceita.css">
    <link rel="shortcut icon" href="../assets/img/icone.png">
</head>

<body>
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
                $modePreparation = $row['modoPreparo'];
                $cost = $row['custo'];
                $preparation_time = $row['tempoPreparo'];
                $observations = $row['observacoes'];
                $category_id = $row['categoria_id'];
                $photo = $row['foto'];

                // Consulta todas as categorias disponíveis
                $sql_categories = "SELECT * FROM categoria";
                $stmt_categories = $pdo->query($sql_categories);
                $categories_options = '';
                while ($category = $stmt_categories->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($category_id == $category['id']) ? 'selected' : '';
                    $categories_options .= "<option value='{$category['id']}' {$selected}>{$category['nomeCategoria']}</option>";
                }
    ?>
                <div class="container">
                    <h2>Editar Receita</h2>
                    <form action="../atualizarReceita.php" method="post" class="form" enctype="multipart/form-data">
                        <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                        <div class="form-group">
                            <label for="photo" class="input-label">Foto do Prato:</label>
                            <?php if (!empty($photo)) : ?>
                                <img src="../<?php echo $photo; ?>" alt="Foto do Prato" style="max-width: 200px; max-height: 200px;">
                            <?php else : ?>
                                <p>Foto não disponível.</p>
                            <?php endif; ?>
                            <input type="file" id="photo" name="photo" class="input-field">
                        </div>
                        <div class="form-group">
                            <label for="name" class="input-label">Nome da Receita:</label>
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required class="input-field">
                        </div>
                        <div class="form-group">
                            <label for="modePreparation" class="input-label">Modo de Preparo:</label>
                            <textarea id="modePreparation" name="modePreparation" rows="4" required class="input-field"><?php echo $modePreparation; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cost" class="input-label">Custo:</label>
                            <input type="text" id="cost" name="cost" value="<?php echo $cost; ?>" required class="input-field">
                        </div>
                        <div class="form-group">
                            <label for="preparation_time" class="input-label">Tempo de Preparo:</label>
                            <input type="text" id="preparation_time" name="preparation_time" value="<?php echo $preparation_time; ?>" required class="input-field">
                        </div>
                        <div class="form-group">
                            <label for="observations" class="input-label">Observações:</label>
                            <input type="text" id="observations" name="observations" value="<?php echo $observations; ?>" required class="input-field">
                        </div>
                        <div class="form-group">
                            <label for="category" class="input-label">Categoria:</label>
                            <select id="category" name="category" required class="input-field">
                                <?php echo $categories_options; ?>
                            </select>
                        </div>
                        <button type="submit" class="buttonRecipe">Atualizar Receita</button>
                    </form>
                </div>
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

    <?php
    include 'footer.php';
    ?>
</body>

</html>