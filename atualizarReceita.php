<?php
session_start();
require_once "config/conecta.php";

// Verifica se o formulário de edição foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recipe_id"])) {
    $recipe_id = $_POST["recipe_id"];
    $name = $_POST["name"];
    $modePreparation = $_POST["modePreparation"];
    $cost = $_POST["cost"];
    $preparation_time = $_POST["preparation_time"];
    $observations = $_POST["observations"];
    $category_id = $_POST["category"];
    $photo_path = '';

    // Verifica se foi enviado um novo arquivo de foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $file_type = mime_content_type($_FILES['photo']['tmp_name']);

        if (array_key_exists($file_type, $allowed_types)) {
            $ext = $allowed_types[$file_type];
            $file_name = time() . ".$ext";
            $photo_path = "arquivos/$file_name";

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], "$photo_path")) {
                echo "Erro ao enviar a imagem.";
                exit;
            }
        } else {
            echo "Formato de imagem inválido. Use JPG ou PNG.";
            exit;
        }
    }

    // Se uma nova foto foi enviada e atualiza o caminho da foto no banco de dados
    if (!empty($photo_path)) {
        $sql = "UPDATE prato SET nome = ?, modoPreparo = ?, custo = ?, tempoPreparo = ?, observacoes = ?, categoria_id = ?, foto = ? WHERE id = ?";
        $params = [$name, $modePreparation, $cost, $preparation_time, $observations, $category_id, $photo_path, $recipe_id];
    } else {
        $sql = "UPDATE prato SET nome = ?, modoPreparo = ?, custo = ?, tempoPreparo = ?, observacoes = ?, categoria_id = ? WHERE id = ?";
        $params = [$name, $modePreparation, $cost, $preparation_time, $observations, $category_id, $recipe_id];
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        header("Location: pages/minhasReceitas.php");
        exit;
    } else {
        echo "Erro ao atualizar a receita.";
    }
} else {
    echo "Requisição inválida.";
}

$pdo = null;
