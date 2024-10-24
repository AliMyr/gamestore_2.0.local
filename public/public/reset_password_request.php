<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Проверяем, существует ли пользователь с данным email
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Генерируем случайный код восстановления
        $reset_code = rand(100000, 999999);

        // Сохраняем код в сессии
        session_start();
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $reset_code;

        echo "Код для восстановления: $reset_code (в реальной системе его нужно отправить по email)";
        header('Location: reset_password.php');
        exit();
    } else {
        echo "Пользователь с таким email не найден.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
</head>
<body>

<h1>Восстановление пароля</h1>

<form method="POST">
    <label for="email">Введите ваш email:</label>
    <input type="email" name="email" id="email" required><br>

    <button type="submit">Отправить код восстановления</button>
</form>

</body>
</html>
