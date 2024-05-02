<?php
session_start();
require_once "../config/conecta.php";

// Verifica se a sessão está iniciada
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Redirecione o usuário para a página de login se não estiver logado
    header("Location: login.php");
    exit; // Encerre o script para garantir que o redirecionamento funcione corretamente
}

// Consulta SQL para selecionar as receitas do usuário atual
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM prato WHERE usuario_id = ?";
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
    <link rel="stylesheet" href="css/style.css">
<body>
    <main>
        <section class="receitas">
            <h2>Suas Receitas</h2>
            <?php if (empty($receitas)): ?>
                <p>Você ainda não cadastrou nenhuma receita.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($receitas as $receita): ?>
                        <li>
                            <h3><a href="detalhesReceitas.php?recipe_id=<?php echo $receita['id']; ?>"><?php echo $receita['nome']; ?></a></h3>
                            <p>Custo: R$ <?php echo number_format($receita['custo'], 2, ',', '.'); ?></p>
                            <p>Tempo de Preparo: <?php echo $receita['tempoPreparo']; ?> minutos</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
