<?php

//nome do servidor 
$server = "localhost";
//nome do banco de dados 
$dbname = "chefVirtual";
//nome do usuario do banco 
$user = "root";
//senha do usuario do banco
$password = "";

//caminho base do site
$base = "http://localhost/chefVirtual";

//

try {
    $pdo = new PDO("mysql:host={$server};dbname={$dbname};charset=utf8;", $user, $password);

    // Verifica se a conexão foi bem-sucedida
    if ($pdo) {
        //echo "Conexão bem-sucedida!";
        // verefica falha
    } else {
        //echo "Falha ao conectar ao banco de dados.";
    }
} catch (Exception $e) {
    //echo "Erro ao conectar no banco de dados {$e->getMessage()}";
    exit;
}
