<?php
  session_start();
  require_once "../config/conecta.php";

  function logout(){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
  }

  require_once "header.php"
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>home</title>
</head>
<body>
  <h1>teste</h1>
</body>
</html>