<?php
function setProfile(&$userProfile)
{
    if (isset($_POST['profileSubmit'])) {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
        $birthdate = isset($_POST['birthdate']) ? trim($_POST['birthdate']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        
        $errors = [];
        
        if (strlen($name) == 0 || !preg_match('/\p{L}/u', $name)) {
            $errors[] = "Ім'я користувача не може бути порожнім і повинно містити хоча б одну літеру.";
        }
        
        // Перевірка прізвища
        if (strlen($surname) == 0 || !preg_match('/\p{L}/u', $surname)) {
            $errors[] = "Прізвище не може бути порожнім і повинно містити хоча б одну літеру.";
        }
        
        // Перевірка дати народження
        if (empty($birthdate)) {
            $errors[] = "Дата народження обов'язкова.";
        } else {
            $birth = DateTime::createFromFormat('Y-m-d', $birthdate);
            if (!$birth) {
                $errors[] = "Неправильний формат дати народження.";
            } else {
                $today = new DateTime();
                $age = $today->diff($birth)->y;
                if ($age < 16) {
                    $errors[] = "Користувачеві має бути не менше 16 років.";
                }
                if ($age > 150) {
                    $errors[] = "Неправдоподібний вік.";
                }
            }
        }
        
        if (strlen($description) < 50) {
            $errors[] = "Стисла інформація має містити не менше 50 символів.";
        }
        
        $uploadedFile = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['photo']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Дозволені тільки файли типу JPEG, PNG, GIF.";
            } else {
                $uploadDir = 'uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = uniqid() . '_' . $_FILES['photo']['name'];
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                    $uploadedFile = $fileName;
                } else {
                    $errors[] = "Помилка завантаження файлу.";
                }
            }
        } elseif (!isset($userProfile['photo'])) {
            $errors[] = "Фото профілю обов'язкове.";
        }
        
        if (empty($errors)) {
            $userProfile['name'] = $name;
            $userProfile['surname'] = $surname;
            $userProfile['birthdate'] = $birthdate;
            $userProfile['description'] = $description;
            if ($uploadedFile) {
                $userProfile['photo'] = $uploadedFile;
            }
            $_SESSION['userProfile'] = $userProfile;
            return true;
        }
        
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
    }
    
    return false;
}

function showBasket($basket)
{
    if (empty($basket)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }
    
    echo "<table border='0' cellspacing='0' cellpadding='3'>";
    echo "<tr>";
    echo "<th>НАЗВА";
    echo "</th>";
    echo "<th>КІЛЬКІСТЬ</th>";
    echo "</tr>";
    
    foreach ($basket as $id => $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        
        echo "<td>" . $item['quantity'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

function addToCart($id, $name, $price, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

function removeFromCart($id) {
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
}

function getCartItems() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    
    return [];
}

function getCartTotal() {
    $total = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    
    return $total;
}

function getCartItemCount() {
    $count = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    
    return $count;
}

function getReceipt($basket)
{
    if (empty($basket)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }
    echo "\n<div class='receipt'>";
    echo "<h2>Чек:</h2>";
    echo "<table border='0' cellspacing='0' cellpadding='5'>";
    echo "<tr>";
    echo "<th>№</th>";
    echo "<th>НАЗВА</th>";
    echo "<th>ЦІНА</th>";
    echo "<th>КІЛЬКІСТЬ</th>";
    echo "<th>ВАРТІСТЬ</th>";
    echo "<th></th>";
    echo "</tr>";
    
    $counter = 1;
    $total = 0;
    
    foreach ($basket as $id => $item) {
        $itemCost = $item['price'] * $item['quantity'];
        
        echo "<tr>";
        echo "<td>$counter</td>";
        echo "<td>" . $item['name'] . "</td>";
        
        $price_str = (string)$item['price'];
        echo "<td>$price_str</td>";
        
        $qty_str = (string)$item['quantity'];
        echo "<td>$qty_str</td>";
        
        echo "<td>$itemCost</td>";
        
        echo "<td><a href='basket.php?action=remove&id=$id' class='remove-button'>🗑️</a></td>";
        
        echo "</tr>";
        $total += $itemCost;
        $counter++;
    }
    
    echo "<tr class='total-row'>";
    echo "<td colspan='4'>РАЗОМ ДО CПЛАТИ:</td>";
    echo "<td>$total</td>";
    echo "<td></td>";
    echo "</tr>";
    
    echo "</table>";
    echo "</div>";
}

function showProducts($products)
    {
        echo "<div class='products-section'>";
        echo "<p>Список товарів:</p>";
        echo "<table border='0' cellspacing='0' cellpadding='5'>";
        echo "<tr>";
        echo "<th>№</th>";
        echo "<th>НАЗВА";
        echo "</th>";
        echo "<th>ЦІНА</th>";
        echo "<th></th>";
        echo "</tr>";

        $keys = array_keys($products);
        for ($i = 0; $i < count($keys); $i++) {
            $number = $keys[$i];
            $prod   = $products[$number];
            $name   = $prod[0];
            $price  = $prod[1];

            echo "<tr>";
            echo "<td>$number</td>";
            echo "<td>$name";
            echo "</td>";

            $price_str = (string)$price;
            echo "<td>$price_str</td>";
            echo "<td>";

            echo "<form method='POST' action='main.php?page=products&action=add' class='add-to-cart-form'>";
            echo "<input type='hidden' name='addIdInput' value='$number'>";
            echo "<input type='number' name='addCountInput' min='1' max='99' value='1' class='count-input' placeholder='1'>";
            echo "<button type='submit' class='button'>Buy</button>";
            echo "</form>";

            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    }

function getReceiptMain($basket)
{
    if (empty($basket)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }
    echo "\n<div class='receipt'>";
    echo "<h2>Чек:</h2>";
    echo "<table border='0' cellspacing='0' cellpadding='5'>";
    echo "<tr>";
    echo "<th>№</th>";
    echo "<th>НАЗВА</th>";
    echo "<th>ЦІНА</th>";
    echo "<th>КІЛЬКІСТЬ</th>";
    echo "<th>ВАРТІСТЬ</th>";
    echo "<th></th>";
    echo "</tr>";
    
    $counter = 1;
    $total = 0;
    
    foreach ($basket as $id => $item) {
        $itemCost = $item['price'] * $item['quantity'];
        
        echo "<tr>";
        echo "<td>$counter</td>";
        echo "<td>" . $item['name'] . "</td>";
        
        $price_str = (string)$item['price'];
        echo "<td>$price_str</td>";
        
        $qty_str = (string)$item['quantity'];
        echo "<td>$qty_str</td>";
        
        echo "<td>$itemCost</td>";
        
        echo "<td><a href='main.php?page=cart&action=remove&id=$id' class='remove-button'>🗑️</a></td>";
        
        echo "</tr>";
        $total += $itemCost;
        $counter++;
    }
    
    echo "<tr class='total-row'>";
    echo "<td colspan='4'>РАЗОМ ДО CПЛАТИ:</td>";
    echo "<td>$total</td>";
    echo "<td></td>";
    echo "</tr>";
    
    echo "</table>";
    echo "</div>";
}
?>