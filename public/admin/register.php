<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Хэшируем пароль для безопасности
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Проверяем, существует ли такой пользователь
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        echo "Пользователь с таким именем уже существует.";
    } else {
        // Вставляем нового пользователя
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        echo "Администратор успешно зарегистрирован!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация администратора</title>
</head>
<body>

<h1>Регистрация администратора</h1>

<form method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Зарегистрироваться</button>
</form>

</body>
</html>
