<?php
    session_start();

    require_once 'functions.php';
    $products = include 'data/products.php';
    $basket   = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'add':
                if (isset($_POST['addIdInput']) && is_numeric($_POST['addIdInput'])) {
                    $id       = (int)$_POST['addIdInput'];
                    $addCount = isset($_POST['addCountInput']) && $_POST['addCountInput'] > 0 ?
                    (int)$_POST['addCountInput'] : 1;

                    if (isset($products[$id])) {
                        if (isset($basket[$id])) {
                            $basket[$id]['quantity'] += $addCount;
                        } else {
                            $basket[$id] = [
                                'name'     => $products[$id][0],
                                'price'    => $products[$id][1],
                                'quantity' => $addCount,
                            ];
                        }

                        $_SESSION['cart'] = $basket;
                    }
                }

                header('Location: index.php');
                exit;
                break;
        }
    }

    include 'header.php';
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

<?php
include 'footer.php';
?>