<?php
session_start();
include '../config/config.php';

$errors = [];

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = trim($_POST['new_email']);
    $new_password = $_POST['new_password'];
    $password_confirm = $_POST['password_confirm'];

    // Валидация email
    if (!empty($new_email)) {
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный формат email.";
        }

        // Проверка на уникальность email
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$new_email, $user_id]);

        if ($stmt->rowCount() > 0) {
            $errors[] = "Этот email уже занят.";
        }
    }

    // Валидация пароля
    if (!empty($new_password)) {
        if ($new_password !== $password_confirm) {
            $errors[] = "Пароли не совпадают.";
        }
    }

    // Если ошибок нет, обновляем данные пользователя
    if (empty($errors)) {
        if (!empty($new_email)) {
            $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$new_email, $user_id]);
            echo "Email успешно обновлён!";
        }

        if (!empty($new_password)) {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $user_id]);
            echo "Пароль успешно обновлён!";
        }
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

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label for="new_email">Новый email:</label>
    <input type="email" name="new_email" id="new_email"><br>

    <label for="new_password">Новый пароль:</label>
    <input type="password" name="new_password" id="new_password"><br>

    <label for="password_confirm">Подтверждение пароля:</label>
    <input type="password" name="password_confirm" id="password_confirm"><br>

    <button type="submit">Обновить профиль</button>
</form>

<p><a href="profile.php">Вернуться в профиль</a></p>

</body>
</html>
