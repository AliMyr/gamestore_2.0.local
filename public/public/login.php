<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ищем пользователя по email
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Авторизация успешна, сохраняем данные в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: profile.php');
        exit();
    } else {
        echo "Неверный email или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>

<h1>Вход в аккаунт</h1>

<form method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Войти</button>
</form>

</body>
</html>
