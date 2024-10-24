<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reset_code = $_POST['reset_code'];
    $new_password = $_POST['new_password'];

    // Проверяем код восстановления
    if ($reset_code == $_SESSION['reset_code']) {
        $email = $_SESSION['reset_email'];

        // Хэшируем новый пароль
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Обновляем пароль пользователя
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);

        // Очищаем сессию восстановления
        unset($_SESSION['reset_code'], $_SESSION['reset_email']);

        echo "Пароль успешно изменён! Теперь вы можете войти.";
        header('Location: login.php');
        exit();
    } else {
        echo "Неверный код восстановления.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля</title>
</head>
<body>

<h1>Сброс пароля</h1>

<form method="POST">
    <label for="reset_code">Введите код восстановления:</label>
    <input type="text" name="reset_code" id="reset_code" required><br>

    <label for="new_password">Введите новый пароль:</label>
    <input type="password" name="new_password" id="new_password" required><br>

    <button type="submit">Сбросить пароль</button>
</form>

</body>
</html>
