<?php
include '../config/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $reset_code = trim($_POST['reset_code']);
    $new_password = $_POST['new_password'];
    $password_confirm = $_POST['password_confirm'];

    // Проверяем код сброса пароля
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND reset_code = ?");
    $stmt->execute([$email, $reset_code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($new_password === $password_confirm) {
            // Хэшируем новый пароль
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

            // Обновляем пароль и удаляем код сброса
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);

            echo "Пароль успешно изменён! Теперь вы можете войти.";
            header('Location: login.php');
            exit();
        } else {
            $errors[] = "Пароли не совпадают.";
        }
    } else {
        $errors[] = "Неверный код сброса или email.";
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

    <label for="reset_code">Введите код сброса пароля:</label>
    <input type="text" name="reset_code" id="reset_code" required><br>

    <label for="new_password">Новый пароль:</label>
    <input type="password" name="new_password" id="new_password" required><br>

    <label for="password_confirm">Подтверждение нового пароля:</label>
    <input type="password" name="password_confirm" id="password_confirm" required><br>

    <button type="submit">Сбросить пароль</button>
</form>

</body>
</html>
