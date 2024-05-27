<?php
session_start();
require_once "../config/conecta.php";

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$receitas_por_pagina = 9;

$user_id = $_SESSION['id'];
$sql_count = "SELECT COUNT(*) AS total FROM prato WHERE usuario_id = ?";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute([$user_id]);
$total_receitas = $stmt_count->fetchColumn();

$total_paginas = ceil($total_receitas / $receitas_por_pagina);

$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

$indice_inicio = ($pagina_atual - 1) * $receitas_por_pagina;

$sql = "SELECT * FROM prato WHERE usuario_id = ? LIMIT $indice_inicio, $receitas_por_pagina";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas</title>
    <link rel="stylesheet" href="../css/minhasReceitas.css">
</head>

<body>
    <main>
        <section class="receitas">
            <h2>Suas Receitas</h2>
            <?php if (empty($receitas)) : ?>
                <div class="naoCadastrada">
                    <div class="img">
                        <img src="../assets/img/coracaoPartido.png" />
                    </div>
                    <div class="txtImg">
                        <p>Você ainda não cadastrou nenhuma receita.</p>
                    </div>
                </div>
            <?php else : ?>
                <ul>
                    <?php foreach ($receitas as $receita) : ?>
                        <li class="receita">
                            <?php if (!empty($receita['foto'])) : ?>
                                <img src="../<?php echo htmlspecialchars($receita['foto']); ?>" alt="Foto do Prato" class="foto-prato">
                            <?php endif; ?>
                            <h3><a href="detalhesReceitas.php?recipe_id=<?php echo $receita['id']; ?>"><?php echo htmlspecialchars($receita['nome']); ?></a></h3>
                            <p class="custo">Custo: R$ <?php echo number_format($receita['custo'], 2, ',', '.'); ?></p>
                            <p class="tempo-preparo">Tempo de Preparo: <?php echo htmlspecialchars($receita['tempoPreparo']); ?> minutos</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="pagination">
                    <?php if ($pagina_atual > 1) : ?>
                        <a href="minhasReceitas.php?pagina=<?php echo ($pagina_atual - 1); ?>">&laquo; Anterior</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                        <?php if ($i == $pagina_atual) : ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else : ?>
                            <a href="minhasReceitas.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($pagina_atual < $total_paginas) : ?>
                        <a href="minhasReceitas.php?pagina=<?php echo ($pagina_atual + 1); ?>">Próxima &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
        require_once 'footer.php';
        ?>
    </main>
</body>

</html>