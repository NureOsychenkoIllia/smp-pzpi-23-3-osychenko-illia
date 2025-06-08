<?php

function handleLogin($page) {
    if ($page !== 'login') return;
    
    if (isset($_SESSION['user_login'])) {
        header('Location: main.php?page=products');
        exit;
    }

    global $error;
    $error = '';
    $credentials = include 'data/credential.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        
        if (empty($username) || empty($password)) {
            $error = 'Всі поля повинні бути заповнені.';
        } elseif (isset($credentials[$username]) && $credentials[$username] === $password) {
            $_SESSION['user_login'] = $username;
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            header('Location: main.php?page=products');
            exit;
        } else {
            $error = 'Неправильне ім\'я користувача або пароль.';
        }
    }
}

function handleProducts($page) {
    if ($page !== 'products' || !isset($_GET['action']) || $_GET['action'] !== 'add') return;
    
    if (!isset($_SESSION['user_login'])) {
        header('Location: main.php?page=login');
        exit;
    }

    if (isset($_POST['addIdInput']) && is_numeric($_POST['addIdInput'])) {
        require_once 'functions.php';
        require_once 'database.php';
        $products = getProducts();
        $basket = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        $id = (int)$_POST['addIdInput'];
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
    header('Location: main.php?page=products');
    exit;
}

function handleCart($page) {
    if ($page !== 'cart') return;
    
    if (!isset($_SESSION['user_login'])) {
        header('Location: main.php?page=login');
        exit;
    }

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'remove':
                if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
                    unset($_SESSION['cart'][$_GET['id']]);
                }
                header('Location: main.php?page=cart');
                exit;
                break;

            case 'clear':
                $_SESSION['cart'] = [];
                header('Location: main.php?page=cart');
                exit;
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
        $totalSum = isset($_SESSION['cart'])
        ? array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart']))
        : 0;
        
        $_SESSION['cart'] = [];
        
        showPaymentSuccess($totalSum);
        exit;
    }
}

function handleProfile($page) {
    if ($page !== 'profile') return;
    
    if (!isset($_SESSION['user_login'])) {
        header('Location: main.php?page=login');
        exit;
    }

    require_once 'functions.php';

    if (!isset($_SESSION['userProfile'])) {
        $_SESSION['userProfile'] = [
            'name' => null, 
            'surname' => null,
            'birthdate' => null,
            'description' => null,
            'photo' => null
        ];
    }

    global $userProfile, $profileUpdated;
    $userProfile = $_SESSION['userProfile'];
    $profileUpdated = setProfile($userProfile);
    $_SESSION['userProfile'] = $userProfile;
}

function showPaymentSuccess($totalSum) {
    ?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
      <meta charset="UTF-8">
      <title>Оплата успішна</title>
      <link rel="stylesheet" href="style.css">
    </head>
    <body>
      <div class="container">
        <div class="empty-cart">
          <h1>Дякуємо!</h1>
          <p>Сплачено: <?php echo number_format($totalSum, 2, '.', ' ') ?> грн</p>
          <a href="main.php?page=products">Повернутися до покупок</a>
        </div>
      </div>
    </body>
    </html>
    <?php
}

function checkAuthorization($page) {
    $protectedPages = ['products', 'cart', 'profile'];
    if (in_array($page, $protectedPages) && !isset($_SESSION['user_login'])) {
        return 'login_required';
    }
    return $page;
}
?>