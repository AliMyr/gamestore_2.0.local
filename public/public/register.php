<?php
require '../../vendor/autoload.php';  // Подключаем autoload.php
include '../config/config.php';       // Подключение базы данных

require 'send_email.php';  // Подключаем файл для отправки email

// Остальной код регистрации



$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Проверка на пустоту
    if (empty($username)) {
        $errors[] = "Имя пользователя не может быть пустым.";
    }
    if (empty($email)) {
        $errors[] = "Email не может быть пустым.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный формат email.";
    }
    if (empty($password)) {
        $errors[] = "Пароль не может быть пустым.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Пароли не совпадают.";
    }

    // Проверка на уникальность email и имени пользователя
    if (empty($errors)) {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            $errors[] = "Пользователь с таким email или именем пользователя уже существует.";
        }
    }

    // Если ошибок нет, регистрируем пользователя
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Генерация кода подтверждения
        $verification_code = rand(100000, 999999);

        // Сохраняем данные пользователя в базе данных
        $stmt = $db->prepare("INSERT INTO users (username, email, password, email_verified, verification_code) VALUES (?, ?, ?, 0, ?)");
        $stmt->execute([$username, $email, $hashedPassword, $verification_code]);

        // Отображаем код подтверждения прямо на экране
        echo "Регистрация успешна! Ваш код подтверждения: $verification_code. Введите его для подтверждения.";
        
        // Переход на страницу для подтверждения
        header('Location: verify_email.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>

<h1>Регистрация</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required><br>

    <label for="password_confirm">Подтверждение пароля:</label>
    <input type="password" name="password_confirm" id="password_confirm" required><br>

    <button type="submit">Зарегистрироваться</button>
</form>

</body>
</html>
