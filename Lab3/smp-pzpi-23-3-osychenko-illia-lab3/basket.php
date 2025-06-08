<?php
    session_start();

    require_once 'functions.php';
    $products = include 'data/products.php';

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'remove':
                if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
                    unset($_SESSION['cart'][$_GET['id']]);
                }
                header('Location: basket.php');
                exit;
                break;

            case 'clear':
                $_SESSION['cart'] = [];
                header('Location: basket.php');
                exit;
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
        $totalSum = isset($_SESSION['cart'])
        ? array_sum(array_column($_SESSION['cart'], 'price'))
        : 0;
    ?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
      <meta charset="UTF-8">
      <title>Оплата успішна</title>
    </head>
    <body>
      <h1>Дякуємо!</h1>
      <p>сплачено: <?php echo number_format($totalSum, 2, '.', ' ') ?> грн</p>
    </body>
    </html>
    <?php
        exit;
        }

        $basket = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        include 'header.php';
    ?>

<div class="container">
    <h1>Кошик</h1>

    <?php if (empty($basket)): ?>
        <div class="empty-cart">
            <p>КОШИК ПОРОЖНІЙ</p>
            <a href="index.php">Перейти до покупок</a>
        </div>
    <?php else: ?>
        <?php getReceipt($basket); ?>

        <div class="cart-buttons">
            <a href="basket.php?action=clear" class="button cancel-button">Очистити</a>
            <form method="post" action="basket.php">
                <button type="submit" class="button submit-button" name="pay">Cплатити</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>