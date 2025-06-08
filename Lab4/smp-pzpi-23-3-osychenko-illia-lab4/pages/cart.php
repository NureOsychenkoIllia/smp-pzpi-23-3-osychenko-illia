<?php
require_once 'functions.php';
$basket = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<div class="container">
    <h1>Кошик</h1>

    <?php if (empty($basket)): ?>
        <div class="empty-cart">
            <p>КОШИК ПОРОЖНІЙ</p>
            <a href="main.php?page=products">Перейти до покупок</a>
        </div>
    <?php else: ?>
        <?php getReceiptMain($basket); ?>

        <div class="cart-buttons">
            <a href="main.php?page=cart&action=clear" class="button cancel-button">Очистити</a>
            <form method="post" action="main.php?page=cart" style="display: inline;">
                <button type="submit" class="button submit-button" name="pay">Cплатити</button>
            </form>
        </div>
    <?php endif; ?>
</div>