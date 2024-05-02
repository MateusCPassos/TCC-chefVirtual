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
        <h1>ChefVirtual</h1>
      </div>

      <div class="nav-list">
        <ul>
          <li class="nav-item"><a href="index.php" class="nav-link">Início</a></li>
          <li class="nav-item"><a href="categoria.php" class="nav-link">Categoria</a></li>
          <li class="nav-item"><a href="favoritos.php" class="nav-link">Favoritos</a></li>
        </ul>
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
        <li><a href="cadastrar.php" class="sidebar-link">Cadastrar</a></li>
        <li><a href="cadastrarReceitas.php" class="sidebar-link">Cadastrar Receitas</a></li>
        <li><a href="minhasReceitas.php" class="sidebar-link">Minhas Receitas</a></li>
        <?php if(isset($_SESSION['id']) && !empty($_SESSION['id'])): ?>
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
