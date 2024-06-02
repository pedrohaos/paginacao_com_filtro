<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja</title>
    <style>
        .item { margin: 20px; display: inline-block; }
        .item img { width: 100px; height: 100px; display: block; }
        .pagination { margin: 20px; }
        .info { margin: 20px; }
    </style>
</head>
<body>
    <form method="GET" action="">
        <label for="items_per_page">Itens por página:</label>
        <select name="items_per_page" id="items_per_page" onchange="this.form.submit()">
            <option value="10" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 10) echo 'selected'; ?>>10</option>
            <option value="15" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 15) echo 'selected'; ?>>15</option>
            <option value="20" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 20) echo 'selected'; ?>>20</option>
            <option value="25" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25) echo 'selected'; ?>>25</option>
        </select>
    </form>

    <?php
    // Conectar ao banco de dados
    $conn = new mysqli('localhost', 'root', '', 'loja');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Configuração de paginação e filtro
    $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $items_per_page;

    // Consulta para obter o total de itens
    $sql = "SELECT COUNT(*) AS total FROM produtos";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_items = $row['total'];
    $total_pages = ceil($total_items / $items_per_page);

    // Exibir informações de paginação
    echo '<div class="info">';
    echo "Página $page de $total_pages<br>";
    echo "Total de itens: $total_items<br>";
    echo '</div>';

    // Consulta SQL para obter os itens
    $sql = "SELECT * FROM produtos LIMIT $offset, $items_per_page";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar itens
        while($row = $result->fetch_assoc()) {
            echo '<div class="item">';
            echo '<img src="' . $row['imagem'] . '" alt="' . $row['nome'] . '">';
            echo '<p>' . $row['nome'] . '</p>';
            echo '</div>';
        }
    } else {
        echo 'Nenhum item encontrado.';
    }

    // Paginação
    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="?page=' . $i . '&items_per_page=' . $items_per_page . '">' . $i . '</a> ';
    }
    echo '</div>';

    $conn->close();
    ?>
</body>
</html>
