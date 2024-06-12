<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["tipoUsuario"] !== 'admin') {
  header("Location: login.php");
  exit;



  require_once '../../pages/header.php';
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>admin</title>
</head>

<body>
  <h1>sistema admin do ChefVirtual</h1>
  <?php require_once 'menu.php'; ?>

</body>

</html>