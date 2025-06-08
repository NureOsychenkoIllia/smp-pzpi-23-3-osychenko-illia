<div class="container">
    <h1>Налаштування профілю</h1>

    <?php if (isset($profileUpdated) && $profileUpdated): ?>
        <div class="success">
            Профіль оновлено: <?php echo htmlspecialchars($userProfile['name']) ?> <?php echo htmlspecialchars($userProfile['surname']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="main.php?page=profile" class="profile-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Ваше ім'я:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($userProfile['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="surname">Ваше прізвище:</label>
            <input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($userProfile['surname'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="birthdate">Дата народження:</label>
            <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($userProfile['birthdate'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Стисла інформація про себе (мінімум 50 символів):</label>
            <textarea name="description" id="description" rows="5" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required><?php echo htmlspecialchars($userProfile['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Фото профілю:</label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <?php if (isset($userProfile['photo']) && $userProfile['photo']): ?>
                <div style="margin-top: 10px;">
                    <p>Поточне фото:</p>
                    <img src="uploads/<?php echo htmlspecialchars($userProfile['photo']) ?>" alt="Profile Photo" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" name="profileSubmit" class="submit-button">Зберегти</button>
    </form>
</div>