<?php
    require_once 'functions.php';
    require_once 'database.php';
    $products = getProducts();
    $basket   = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<div class="container">
    <h1>Список товарів</h1>

    <?php
        showProducts($products);
    ?>

    <div style="margin-top: 20px;">
        <h2>У КОШИКУ:</h2>
        <?php showBasket($basket); ?>
    </div>
</div>