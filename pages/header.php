<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>header</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
  <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
  <header>
    <nav class="nav-bar">
      <div class="logo">
        <a href="index.php"><img src="../assets/img/logoFinal.png" ></a>
      </div>

      <div class="menu-icon">
        <i class="fa-solid fa-bars"></i>
      </div>
    </nav>

    <aside class="sidebar">
      <div class="sidebar-header">
        <i class="fa-solid fa-arrow-right close-button"></i>
      </div>
      <ul class="sidebar-list">

        <?php
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }

        if (!isset($_SESSION["id"])) {
          echo '<li><a href="cadastrar.php" class="sidebar-link">Cadastrar/entrar</a></li>';
        }
        ?>
        <li><a href="index.php" class="sidebar-link">Início</a></li>
        <li><a href="categoria.php" class="sidebar-link">Categoria</a></li>
        <li><a href="favoritos.php" class="sidebar-link">Favoritos</a></li>
        <li><a href="cadastrarReceitas.php" class="sidebar-link">Cadastrar Receitas</a></li>
        <li><a href="minhasReceitas.php" class="sidebar-link">Minhas Receitas</a></li>
        <?php if (isset($_SESSION['id']) && !empty($_SESSION['id'])) : ?>
          <li><a href="../logout.php" class="sidebar-link">Sair</a></li>
        <?php endif; ?>
      </ul>
    </aside>

  </header>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const menuIcon = document.querySelector(".menu-icon");
      const sidebar = document.querySelector(".sidebar");
      const closeButton = document.querySelector(".close-button");

      menuIcon.addEventListener("click", function() {
        sidebar.classList.toggle("active");
      });


      closeButton.addEventListener("click", function() {
        sidebar.classList.remove("active");
      });
    });
  </script>
</body>

</html>
