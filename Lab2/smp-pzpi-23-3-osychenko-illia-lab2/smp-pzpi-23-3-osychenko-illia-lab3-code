<?php

$products = [
    1 => ["Молоко пастеризоване", 12],
    2 => ["Хліб чорний", 9],
    3 => ["Сир білий", 21],
    4 => ["Сметана 20%", 25],
    5 => ["Кефір 1%", 19],
    6 => ["Вода газована", 18],
    7 => ['Печиво "Весна"', 14],
];

$basket = [];

$userProfile = ['name' => null, 'age' => null];

function readInput($prompt = "")
{
    if ($prompt) {
        echo $prompt;
    }
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    return trim($line);
}

function ua_strlen($str)
{
    $length = 0;
    $i = 0;
    $len = strlen($str);
    while ($i < $len) {
        $byte = ord($str[$i]);
        if ($byte < 128) {
            $length++;
            $i++;
        } elseif ($byte >= 192 && $byte < 224) { # українська літера >= 128 <= 224
            $length++;
            $i += 2;
        } else {
            $i++;
            $length++;
        }
    }
    return $length;
}

function showMainMenu()
{
    echo "################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
}

function productSelection(&$products, &$basket)
{
    while (true) {
        showProducts($products);
        $prodInput = readInput("Виберіть товар: ");
        if (!is_numeric($prodInput)) {
            echo "ПОМИЛКА! Введено не число.\n";
            continue;
        }
        $prodNum = (int) $prodInput;
        if ($prodNum === 0) {
            break;
        }
        if (!isset($products[$prodNum])) {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
            continue;
        }

        echo "Вибрано: " . $products[$prodNum][0] . "\n";
        $qtyInput = readInput("Введіть кількість, штук: ");
        if (!is_numeric($qtyInput)) {
            echo "ПОМИЛКА! Кількість має бути числом.\n";
            continue;
        }
        $quantity = (int) $qtyInput;
        if ($quantity === 0) {
            if (isset($basket[$prodNum])) {
                unset($basket[$prodNum]);
                echo "ВИДАЛЯЮ З КОШИКА\n";
            } else {
                echo "Товар \"" . $products[$prodNum][0] . "\" відсутній у кошику.\n";
            }
            showBasket($basket);
            continue;
        }
        if ($quantity < 0 || $quantity >= 100) {
            echo "ПОМИЛКА! Кількість має бути від 1 до 99.\n";
            continue;
        }

        $basket[$prodNum] = [
            'name' => $products[$prodNum][0],
            'price' => $products[$prodNum][1],
            'quantity' => $quantity,
        ];
        showBasket($basket);
    }
}

function showBasket($basket)
{
    if (empty($basket)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }

    $NAME_COL_WIDTH = 22;
    $QTY_COL_WIDTH = 10;

    echo "У КОШИКУ:\n";
    echo "НАЗВА";
    for ($i = 5; $i < $NAME_COL_WIDTH; $i++) {
        echo ' ';
    }
    echo "КІЛЬКІСТЬ\n";

    foreach ($basket as $item) {
        echo $item['name'];
        $name_length = ua_strlen($item['name']);
        for ($j = $name_length; $j < $NAME_COL_WIDTH; $j++) {
            echo ' ';
        }

        echo $item['quantity'] . "\n";
    }
}

function getReceipt($basket)
{
    if (empty($basket)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }

    $NAME_COL_WIDTH = 22;
    $PRICE_COL_WIDTH = 6;
    $QTY_COL_WIDTH = 10;

    echo "\nЧек:\n";
    echo "№  НАЗВА";
    for ($i = 5; $i < $NAME_COL_WIDTH; $i++) {
        echo ' ';
    }
    echo "ЦІНА";
    for ($i = 4; $i < $PRICE_COL_WIDTH; $i++) {
        echo ' ';
    }
    echo "КІЛЬКІСТЬ  ВАРТІСТЬ\n";

    $counter = 1;
    $total = 0;

    foreach ($basket as $item) {
        $itemCost = $item['price'] * $item['quantity'];

        echo $counter . "  ";

        echo $item['name'];
        $name_length = ua_strlen($item['name']);
        for ($j = $name_length; $j < $NAME_COL_WIDTH; $j++) {
            echo ' ';
        }

        $price_str = (string) $item['price'];
        echo $price_str;
        $price_length = ua_strlen($price_str);
        for ($j = $price_length; $j < $PRICE_COL_WIDTH; $j++) {
            echo ' ';
        }

        $qty_str = (string) $item['quantity'];
        echo $qty_str;
        $qty_length = ua_strlen($qty_str);
        for ($j = $qty_length; $j < $QTY_COL_WIDTH; $j++) {
            echo ' ';
        }

        echo $itemCost;

        echo "\n";
        $total += $itemCost;
        $counter++;
    }

    echo "РАЗОМ ДО CПЛАТИ: " . $total . "\n";
}

function showProducts(array $products)
{
    $NAME_COL_WIDTH = 22;
    $PRICE_COL_WIDTH = 4;

    echo "Список товарів:\n";
    echo "№  НАЗВА";
    for ($i = 5; $i < $NAME_COL_WIDTH; $i++) {
        echo ' ';
    }
    echo "ЦІНА\n";

    $keys = array_keys($products);
    for ($i = 0; $i < count($keys); $i++) {
        $number = $keys[$i];
        $prod = $products[$number];
        $name = $prod[0];
        $price = $prod[1];

        echo $number . "  ";

        echo $name;
        $name_length = ua_strlen($name);
        for ($j = $name_length; $j < $NAME_COL_WIDTH; $j++) {
            echo ' ';
        }

        $price_str = (string) $price;
        echo $price_str;
        $price_length = ua_strlen($price_str);
        for ($j = $price_length; $j < $PRICE_COL_WIDTH; $j++) {
            echo ' ';
        }
        echo "\n";
    }

    echo "   -----------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
}

function setProfile(&$userProfile)
{
    while (true) {
        $name = readInput("Ваше імʼя: ");
        if (strlen(trim($name)) == 0 || !preg_match('/\p{L}/u', $name)) {
            echo "Імʼя користувача не може бути порожнім і повинно містити хоча б одну літеру.\n";
        } else {
            $userProfile['name'] = $name;
            break;
        }
    }
    while (true) {
        $ageInput = readInput("Ваш вік: ");
        if (!is_numeric($ageInput)) {
            echo "Вік має бути числовим значенням.\n";
            continue;
        }
        $age = (int) $ageInput;
        if ($age < 7 || $age > 150) {
            echo "Користувач не може бути молодшим 7-ми або старшим 150-ти років.\n";
        } else {
            $userProfile['age'] = $age;
            break;
        }
    }
    echo "Профіль встановлено: " . $userProfile['name'] . " (" . $userProfile['age'] . " років)\n";
}

while (true) {
    showMainMenu();
    $command = readInput("Введіть команду: ");
    if (!is_numeric($command)) {
        echo "ПОМИЛКА! Введіть правильну команду\n";
        continue;
    }
    $cmd = (int) $command;
    if (!in_array($cmd, [0, 1, 2, 3])) {
        echo "ПОМИЛКА! Введіть правильну команду\n";
        continue;
    }
    switch ($cmd) {
        case 1:
            productSelection($products, $basket);
            break;
        case 2:
            getReceipt($basket);
            break;
        case 3:
            setProfile($userProfile);
            break;
        case 0:
            echo "Вихід з програми. До побачення!\n";
            exit(0);
    }
}

