<?php
 session_start();

 require_once 'functions.php';

 if (! isset($_SESSION['userProfile'])) {
  $_SESSION['userProfile'] = ['name' => null, 'age' => null];
 }

 $userProfile = $_SESSION['userProfile'];

 $profileUpdated          = setProfile($userProfile);
 $_SESSION['userProfile'] = $userProfile;

 include 'header.php';
?>

<div class="container">
    <h1>Налаштування профілю</h1>

    <?php if ($profileUpdated): ?>
        <div class="success">
            Профіль встановлено: <?php echo $userProfile['name'] ?> (<?php echo $userProfile['age'] ?> років)
        </div>
    <?php endif; ?>

    <form method="POST" action="profile.php" class="profile-form">
        <div class="form-group">
            <label for="name">Ваше ім'я:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($userProfile['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="age">Ваш вік:</label>
            <input type="number" name="age" id="age" value="<?php echo $userProfile['age'] ?? '' ?>">
        </div>

        <button type="submit" name="profileSubmit" class="submit-button">Зберегти</button>
    </form>
</div>

<?php
include 'footer.php';
?>