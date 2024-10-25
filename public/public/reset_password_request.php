<?php
include '../config/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Проверяем, существует ли пользователь с указанным email
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Генерируем случайный код для сброса пароля
        $reset_code = rand(100000, 999999);

        // Сохраняем код сброса в базе данных
        $stmt = $db->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
        $stmt->execute([$reset_code, $email]);

        // Выводим код на экран для тестирования (в реальной системе код можно отправлять на email)
        echo "Ваш код для сброса пароля: $reset_code. Введите его на следующей странице для сброса пароля.";
        header('Location: reset_password.php');
        exit();
    } else {
        $errors[] = "Пользователь с таким email не найден.";
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

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label for="email">Введите ваш email:</label>
    <input type="email" name="email" id="email" required><br>

    <button type="submit">Отправить код для сброса пароля</button>
</form>

</body>
</html>
