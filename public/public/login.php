<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Проверка на пустоту полей
    if (empty($email)) {
        $errors[] = "Email не может быть пустым.";
    }
    if (empty($password)) {
        $errors[] = "Пароль не может быть пустым.";
    }

    // Если ошибок нет, продолжаем
    if (empty($errors)) {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // Успешный вход, сохраняем данные пользователя в сессию
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Перенаправляем на главную страницу или в профиль
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Неправильный email или пароль.";
        }
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

<h1>Вход</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Войти</button>
</form>

<p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>

</body>
</html>
