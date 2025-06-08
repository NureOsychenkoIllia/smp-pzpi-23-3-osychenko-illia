<?php
session_start();

require_once 'handlers.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'products';

handleLogin($page);
handleProducts($page);
handleCart($page);
handleProfile($page);

$page = checkAuthorization($page);

include 'header.php';

switch ($page) {
    case "cart":
        require_once("pages/cart.php");
        break;
    case "profile":
        require_once("pages/profile.php");
        break;
    case "products":
        require_once("pages/products.php");
        break;
    case "login":
        include("pages/login.php");
        break;
    case "login_required":
        require_once("unauthorized.php");
        break;
    default:
        require_once("unauthorized.php");
        break;
}

include 'footer.php';
?>