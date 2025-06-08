<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ПРОДОВОЛЬЧИЙ МАГАЗИН "ВЕСНА"</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="nav">
            <?php if (isset($_SESSION['user_login'])): ?>
                <a href="main.php?page=products">📋 Products</a>
                <a href="main.php?page=cart">🛒 Cart</a>
                <a href="main.php?page=profile">👤 Profile</a>
                <a href="logout.php">🚪 Logout</a>
            <?php else: ?>
                <a href="main.php?page=login">🔐 Login</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container">
        <div class="shop-header">
            <h1>ПРОДОВОЛЬЧИЙ МАГАЗИН "ВЕСНА"</h1>
            <?php 
            if (isset($_SESSION['user_login'])) {
                echo '<div class="user-greeting">Вітаємо, ' . htmlspecialchars($_SESSION['user_login']) . '!</div>';
                if (isset($_SESSION['userProfile']) && $_SESSION['userProfile']['name']) {
                    echo '<div class="user-greeting">(' . htmlspecialchars($_SESSION['userProfile']['name']) . ')</div>';
                }
            }
            ?>
        </div>
    </div>