<?php

function initDatabase() {
    $dbPath = 'data/shop.db';
    
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }
    
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY,
            name TEXT NOT NULL,
            price REAL NOT NULL
        )";
        $pdo->exec($sql);
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            $products = [
                [1, "Молоко пастеризоване", 12],
                [2, "Хліб чорний", 9],
                [3, "Сир білий", 21],
                [4, "Сметана 20%", 25],
                [5, "Кефір 1%", 19],
                [6, "Вода газована", 18],
                [7, 'Печиво "Весна"', 14],
            ];
            
            $stmt = $pdo->prepare("INSERT INTO products (id, name, price) VALUES (?, ?, ?)");
            foreach ($products as $product) {
                $stmt->execute($product);
            }
        }
        
        return $pdo;
    } catch (PDOException $e) {
        die('Помилка бази даних: ' . $e->getMessage());
    }
}

function getProducts() {
    static $products = null;
    
    if ($products === null) {
        $pdo = initDatabase();
        $stmt = $pdo->query("SELECT id, name, price FROM products ORDER BY id");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $products = [];
        foreach ($rows as $row) {
            $products[$row['id']] = [$row['name'], $row['price']];
        }
    }
    
    return $products;
}
?>