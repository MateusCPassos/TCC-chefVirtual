<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Materiais</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Cadastro de Materiais</h2>
    <form action="../cadastroReceitas2.php" method="post">
        <div class="form-group">
            <label for="material">Material:</label>
            <input type="text" id="material" name="material">
        </div>
        <button type="button" id="addMaterial">Adicionar Material</button>
        <ul id="materialList"></ul>
        <input type="hidden" name="recipe_id" value="<?php echo $_SESSION['recipe_id']; ?>">
        <button type="submit">Salvar Materiais</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const materialInput = document.getElementById('material');
            const addMaterialBtn = document.getElementById('addMaterial');
            const materialList = document.getElementById('materialList');

            addMaterialBtn.addEventListener('click', function() {
                const material = materialInput.value.trim();
                if (material !== '') {
                    const li = document.createElement('li');
                    li.textContent = material;
                    materialList.appendChild(li);
                    materialInput.value = '';
                }
            });
        });
    </script>
</body>

</html>