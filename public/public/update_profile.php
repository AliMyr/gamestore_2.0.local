<?php
session_start();
include '../config/config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Обработка изменения email и пароля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = $_POST['new_email'];
    $new_password = $_POST['new_password'];

    // Обновляем email
    if (!empty($new_email)) {
        // Проверка на уникальность email
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$new_email]);
        if ($stmt->rowCount() == 0) {
            $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$new_email, $user_id]);
            echo "Email успешно обновлён!";
        } else {
            echo "Этот email уже занят.";
        }
    }

    // Обновляем пароль
    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $user_id]);
        echo "Пароль успешно обновлён!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обновление профиля</title>
</head>
<body>

<h1>Обновление профиля</h1>

<form method="POST">
    <label for="new_email">Новый email:</label>
    <input type="email" name="new_email" id="new_email"><br>

    <label for="new_password">Новый пароль:</label>
    <input type="password" name="new_password" id="new_password"><br>

    <button type="submit">Обновить профиль</button>
</form>

<p><a href="profile.php">Вернуться в профиль</a></p>

</body>
</html>
