<?php
require_once "../config/conecta.php";
require_once "header.php";

// Verifica se o ID da receita foi passado via GET
if (isset($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];

    // Consulta os detalhes da receita no banco de dados
    $sql = "SELECT * FROM prato WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$recipe_id])) {
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['nome'];
            $description = $row['modoPreparo'];
            $cost = $row['custo'];
            $preparation_time = $row['tempoPreparo'];
            $observations = $row['observacoes'];
            $category_id = $row['categoria_id'];

            // Consulta o nome da categoria
            $sql_category = "SELECT nomeCategoria FROM categoria WHERE id = ?";
            $stmt_category = $pdo->prepare($sql_category);
            if ($stmt_category->execute([$category_id])) {
                if ($row_category = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                    $category_name = $row_category['nomeCategoria'];
                }
            } else {
                echo "Erro ao consultar a categoria.";
            }

            // Exibe os detalhes da receita
            echo "<h2>$name</h2>";
            echo "<p>Descrição: $description</p>";
            echo "<p>Custo: R$ $cost</p>";
            echo "<p>Tempo de Preparo: $preparation_time</p>";
            echo "<p>Observações: $observations</p>";
            echo "<p>Categoria: $category_name</p>";

?>
            <h2>Materiais cadastrados:</h2>
            <?php
            $sql_materiais = "SELECT m.id, m.nomeMaterial, mp.prato_id FROM materiais_has_prato mp
                              INNER JOIN materiais m ON (m.id = mp.materiais_id)
                              WHERE mp.prato_id = ? ORDER BY m.nomeMaterial";
            $stmt_materiais = $pdo->prepare($sql_materiais);
            $stmt_materiais->execute([$recipe_id]);
            $materiais = $stmt_materiais->fetchAll(PDO::FETCH_OBJ);

            foreach ($materiais as $material) {
            ?>
                <p><?= $material->nomeMaterial ?></p>
            <?php
            }
            ?>

            <h2>Ingredientes cadastrados:</h2>
            <?php
            $sql_ingredientes = "SELECT i.id, i.NomeIndrediente, pi.quantidade FROM prato_has_indredientes pi
                                 INNER JOIN indredientes i ON (i.id = pi.indredientes_id)
                                 WHERE pi.prato_id = ?";
            $stmt_ingredientes = $pdo->prepare($sql_ingredientes);
            $stmt_ingredientes->execute([$recipe_id]);
            $ingredientes = $stmt_ingredientes->fetchAll(PDO::FETCH_OBJ);

            foreach ($ingredientes as $ingrediente) {
            ?>
                <p><?= $ingrediente->NomeIndrediente ?> - <?= $ingrediente->quantidade ?></p>
<?php
            }

            echo '<a href="editarReceita.php?recipe_id=' . $recipe_id . '">alterar informações da receita</a>';
            echo '<a href="cadastrarReceitas2.php?recipe_id=' . $recipe_id . '">Alterar Materiais Receita</a>';
            echo '<a href="cadastrarReceitas3.php?recipe_id=' . $recipe_id . '">Alterar indredients Receita</a>';
        } else {
            echo "Receita não encontrada.";
        }
    } else {
        echo "Erro ao consultar a receita.";
    }
} else {
    echo "ID da receita não foi especificado.";
}

$pdo = null;
?>