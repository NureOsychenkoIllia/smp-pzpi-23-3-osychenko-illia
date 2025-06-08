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
            <a href="index.php">📋 Products</a>
            <a href="basket.php">🛒 Cart</a>
            <a href="profile.php">👤 Profile</a>
        </div>
    </div>
    
    <div class="container">
        <div class="shop-header">
            <h1>ПРОДОВОЛЬЧИЙ МАГАЗИН "ВЕСНА"</h1>
            <?php 
            if (isset($_SESSION['userProfile']) && $_SESSION['userProfile']['name']) {
                echo '<div class="user-greeting">Вітаємо, ' . htmlspecialchars($_SESSION['userProfile']['name']) . '!</div>';
            }
            ?>
        </div>
    </div>