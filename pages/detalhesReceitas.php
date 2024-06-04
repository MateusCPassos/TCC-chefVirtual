<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes Receita</title>
    <link rel="stylesheet" href="../css/detalhesReceita.css">
</head>

<body>
    <?php
    session_start();
    require_once "../config/conecta.php";
    require_once "header.php";

    // Verifica se o usuário está autenticado
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    // Função para excluir a receita
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recipe_id"])) {
        $recipe_id = $_POST["recipe_id"];
        $user_id = $_SESSION['id'];

        try {
            // Inicia uma transação
            $pdo->beginTransaction();

            // Exclui os registros relacionados nas tabelas de dependência
            $sql_favoritos = "DELETE FROM favoritos WHERE prato_id = ?";
            $stmt_favoritos = $pdo->prepare($sql_favoritos);
            $stmt_favoritos->execute([$recipe_id]);

            $sql_prato_ingredientes = "DELETE FROM prato_has_indredientes WHERE prato_id = ?";
            $stmt_prato_ingredientes = $pdo->prepare($sql_prato_ingredientes);
            $stmt_prato_ingredientes->execute([$recipe_id]);

            $sql_prato_materiais = "DELETE FROM materiais_has_prato WHERE prato_id = ?";
            $stmt_prato_materiais = $pdo->prepare($sql_prato_materiais);
            $stmt_prato_materiais->execute([$recipe_id]);

            $sql_log = "DELETE FROM log WHERE prato_id = ?";
            $stmt_log = $pdo->prepare($sql_log);
            $stmt_log->execute([$recipe_id]);

            // Define a consulta SQL para excluir o prato
            $sql_prato = "DELETE FROM prato WHERE id = ? AND usuario_id = ?";
            $stmt_prato = $pdo->prepare($sql_prato);

            // Executa a consulta SQL, passando o ID do prato e o ID do usuário como parâmetros
            if ($stmt_prato->execute([$recipe_id, $user_id])) {
                // Confirma a transação
                $pdo->commit();
                // Prato excluído com sucesso, redireciona de volta para a página anterior
                header("Location: index.php?mensagem=Item excluído com sucesso.");
                exit();
            } else {
                // Se houver um erro ao excluir o prato, desfaz a transação e exibe uma mensagem de erro
                $pdo->rollBack();
                echo "Erro ao excluir o item.";
            }
        } catch (PDOException $e) {
            // Se ocorrer um erro, desfaz a transação e exibe a mensagem de erro
            $pdo->rollBack();
            echo "Erro: " . $e->getMessage();
        }
    }

    // Verifica se o ID da receita foi passado via GET
    if (isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];
        $user_id = $_SESSION['id'];

        // Consulta os detalhes da receita no banco de dados
        $sql = "SELECT * FROM prato WHERE id = ? AND usuario_id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$recipe_id, $user_id])) {
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $name = $row['nome'];
                $description = $row['modoPreparo'];
                $cost = $row['custo'];
                $preparation_time = $row['tempoPreparo'];
                $observations = $row['observacoes'];
                $category_id = $row['categoria_id'];
                $foto = $row['foto']; // Adiciona a foto do prato

                // Verifica o caminho completo do arquivo
                $foto_path = "../" . $foto;

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
    ?>
                <div class="nome">
                    <?php
                    // Exibe os detalhes da receita
                    echo "<h2>$name</h2>";
                    ?>
                    <div class="foto">
                        <?php
                        // Verifica se a foto existe
                        if ($foto && file_exists($foto_path)) {
                            echo "<img src='$foto_path' alt='$name' class='foto-prato' style='max-width: 100%; height: auto;'>";
                        } else {
                            echo "<p>Foto não disponível. Caminho verificado: $foto_path</p>";
                            // Adiciona informações de depuração
                            if (!$foto) {
                                echo "<p>Foto não especificada no banco de dados.</p>";
                            } elseif (!file_exists($foto_path)) {
                                echo "<p>Arquivo não encontrado no caminho: $foto_path</p>";
                            }
                        } ?>
                    </div>

                </div>
                <?php
                // Exibe os ingredientes cadastrados para a receita
                echo "<h2>Ingredientes:</h2>";
                $sql_ingredientes = "SELECT i.id, i.NomeIndrediente, pi.quantidade FROM prato_has_indredientes pi
                                             INNER JOIN indredientes i ON (i.id = pi.indredientes_id)
                                             WHERE pi.prato_id = ?";
                $stmt_ingredientes = $pdo->prepare($sql_ingredientes);
                $stmt_ingredientes->execute([$recipe_id]);
                $ingredientes = $stmt_ingredientes->fetchAll(PDO::FETCH_OBJ);

                foreach ($ingredientes as $ingrediente) {
                    echo "<p>$ingrediente->NomeIndrediente - $ingrediente->quantidade</p>";
                }

                echo "<p>Modo Preparo: $description</p>";
                // Exibe os materiais cadastrados para a receita
                echo "<h2>Materiais cadastrados:</h2>";
                $sql_materiais = "SELECT m.id, m.nomeMaterial, mp.prato_id FROM materiais_has_prato mp
                              INNER JOIN materiais m ON (m.id = mp.materiais_id)
                              WHERE mp.prato_id = ? ORDER BY m.nomeMaterial";
                $stmt_materiais = $pdo->prepare($sql_materiais);
                $stmt_materiais->execute([$recipe_id]);
                $materiais = $stmt_materiais->fetchAll(PDO::FETCH_OBJ);

                foreach ($materiais as $material) {
                    echo "<p>$material->nomeMaterial</p>";
                }
                echo "<p>Custo: R$ $cost</p>";
                echo "<p>Tempo de Preparo: $preparation_time</p>";
                echo "<p>Categoria: $category_name</p>";
                echo "<p>Observações: $observations</p>";

                // Botão de exclusão da receita
                ?>
                <form id="delete-form" action="" method="POST" onsubmit="return confirmDelete()">
                    <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                    <button type="submit">Excluir</button>
                </form>

    <?php

                echo '<hr>';
                echo '<h2>Modificações da receita</h2>';
                echo '<a href="editarReceita.php?recipe_id=' . $recipe_id . '">Alterar informações da receita</a>';
                echo '<a href="cadastrarReceitas2.php?recipe_id=' . $recipe_id . '">Alterar Materiais Receita</a>';
                echo '<a href="cadastrarReceitas3.php?recipe_id=' . $recipe_id . '">Alterar ingredientes Receita</a>';
            } else {
                echo "Receita não encontrada ou não pertence ao usuário.";
            }
        } else {
            echo "Erro ao consultar a receita.";
        }
    } else {
        echo "ID da receita não foi especificado.";
    }

    $pdo = null;
    ?>
    <script>
        function confirmDelete() {
            return confirm("Tem certeza de que deseja excluir esta receita?");
        }
    </script>
</body>

</html>