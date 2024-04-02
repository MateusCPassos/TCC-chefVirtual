<?php

require_once "config/conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

    // Verifica se todos os campos obrigatórios estão preenchidos
    if(empty($name) || empty($email) || empty($password)){
        echo "Por favor, preencha todos os campos.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $uuid = uniqid(); // Gera um UUID único

        // Verifica se o usuário já existe
        $stmt_check = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt_check->execute([$email]);
        $existing_user = $stmt_check->fetch(PDO::FETCH_ASSOC);
        $stmt_check->closeCursor(); // Fecha o cursor após a verificação

        if ($existing_user) {
            echo "Usuário já existe";
        } else {
            // Insere o novo usuário com UUID
            $sql = "INSERT INTO usuario (uuid, name, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$uuid, $name, $email, $hashed_password])) {
                echo "Usuário criado";
            } else {
                echo "Erro ao criar usuário";
            }
            $stmt->closeCursor(); // Fecha o cursor após a inserção
        }
    }
}

$pdo = null; // Encerra a conexão com o banco de dados após o término do script
?>
