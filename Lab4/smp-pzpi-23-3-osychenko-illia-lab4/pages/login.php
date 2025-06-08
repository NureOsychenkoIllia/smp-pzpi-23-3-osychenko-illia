<?php
if (isset($_SESSION['user_login'])) {
    header('Location: main.php?page=products');
    exit;
}

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
?>

<div class="container">
    <h1>Авторизація</h1>
    
    <?php if ($error): ?>
        <div class="error">
            <?php echo htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="main.php?page=login" class="profile-form">
        <div class="form-group">
            <label for="username">Ім'я користувача:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <button type="submit" class="submit-button">Увійти</button>
    </form>
    
    <div class="login-info">
        <p>Тестові облікові записи:</p>
        <p><strong>Test</strong> / 123123</p>
        <p><strong>admin</strong> / admin123</p>
    </div>
</div>