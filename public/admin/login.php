<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ищем пользователя в базе данных
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Авторизация успешна, сохраняем пользователя в сессии
        $_SESSION['user_id'] = $user['id'];
        header('Location: admin.php');
        exit();
    } else {
        echo "Неправильное имя пользователя или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ панель</title>
</head>
<body>

<h1>Вход в админ панель</h1>

<form method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Войти</button>
</form>

</body>
</html>
