<?php
require_once "config/conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $profilePic = null;

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (empty($name) || empty($email) || empty($password)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Verifica se a senha tem pelo menos 6 caracteres
        if (strlen($password) < 6) {
            echo "A senha deve ter pelo menos 6 caracteres.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Verifica se o usuário já existe
            $stmt_check = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
            $stmt_check->execute([$email]);
            $existing_user = $stmt_check->fetch(PDO::FETCH_ASSOC);
            $stmt_check->closeCursor();
            if ($existing_user) {
                echo "<script>alert('Este e-mail já está sendo utilizado.');</script>";
            } else {
                // Processa o upload da imagem
                if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == UPLOAD_ERR_OK) {
                    $uploadDir = 'arquivosUsuario/';
                    $content = mime_content_type($_FILES['profile-pic']['tmp_name']);
                    if ($content == "image/jpeg" || $content == "image/png") {
                        $tipo = ($content == "image/jpeg") ? "jpg" : "png";
                        $nomeArquivo = time() . ".{$tipo}";
                        $uploadFile = $uploadDir . $nomeArquivo;
                        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $uploadFile)) {
                            $profilePic = "arquivosUsuario/{$nomeArquivo}";
                        } else {
                            echo "<script>alert('Erro ao enviar a imagem. Tente novamente.');</script>";

                            exit;
                        }
                    } else {
                        echo "<script>alert('Erro ao enviar imagem, selecione um arquivo JPG ou PNG válido.');</script>";
                        exit;
                    }
                }

                // Insere o novo usuário
                $sql = "INSERT INTO usuario (nome, email, senha, fotoUsuariocol, tipoUsuario) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);

                // Define o tipo de usuário como "comum"
                $tipoUsuario = "comum";

                if ($stmt->execute([$name, $email, $hashed_password, $profilePic, $tipoUsuario])) {
                    header("Location: pages/login.php");
                    exit();
                } else {
                    echo "Erro ao criar usuário.";
                }
                $stmt->closeCursor();
            }
        }
    }
}

$pdo = null;
