<?php
session_start();
require_once "../config/conecta.php";

function logout()
{
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

require_once "header.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>home</title>
  <link rel="stylesheet" href="../css/index.css">
</head>

<body>
  <main>
    <form method="GET" action="buscarReceita.php" class="form-busca">
      <input type="text" name="search" placeholder="Buscar receita" required>
      <button type="submit">Buscar</button>
    </form>

  </main>
</body>

</html>