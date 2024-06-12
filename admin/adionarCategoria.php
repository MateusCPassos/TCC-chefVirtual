<?php
session_start();
require_once "../config/conecta.php";

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["tipoUsuario"] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCategoria = isset($_POST["nomeCategoria"]) ? trim($_POST["nomeCategoria"]) : '';
    $categoriaPic = null;

    // Verifica se o campo obrigatório está preenchido
    if (empty($nomeCategoria)) {
        echo "Por favor, preencha o nome da categoria.";
    } else {
        // Verifica se o diretório de upload existe, caso contrário, cria-o
        $uploadDir = '../arquivosCategoria/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Processa o upload da imagem
        if (isset($_FILES['categoria-pic']) && $_FILES['categoria-pic']['error'] == UPLOAD_ERR_OK) {
            $content = mime_content_type($_FILES['categoria-pic']['tmp_name']);
            if ($content == "image/jpeg" || $content == "image/png") {
                $tipo = ($content == "image/jpeg") ? "jpg" : "png";
                $nomeArquivo = time() . ".{$tipo}";
                $uploadFile = $uploadDir . $nomeArquivo;
                if (move_uploaded_file($_FILES['categoria-pic']['tmp_name'], $uploadFile)) {
                    $categoriaPic = "arquivosCategoria/{$nomeArquivo}";
                } else {
                    echo "<p>Erro ao enviar a imagem. Tente novamente.</p>";
                    exit;
                }
            } else {
                echo "<p>Erro ao enviar imagem, selecione um arquivo JPG ou PNG válido</p>";
                exit;
            }
        }

        // Insere a nova categoria
        $sql = "INSERT INTO categoria (nomeCategoria, fotoCategoria) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nomeCategoria, $categoriaPic])) {
            echo "Categoria adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar categoria.";
        }
        $stmt->closeCursor();
    }
}

$pdo = null;
