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
        // Проверяем, не подтверждён ли email
        if ($user['email_verified'] == 0) {
            // Генерируем новый код подтверждения
            $verification_code = rand(100000, 999999);

            // Обновляем код подтверждения в базе данных
            $stmt = $db->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
            $stmt->execute([$verification_code, $email]);

            // В реальной системе код нужно отправить по email
            echo "Ваш новый код подтверждения: $verification_code (в реальной системе он будет отправлен по email)";
            header('Location: verify_email.php');
            exit();
        } else {
            $errors[] = "Этот email уже подтверждён.";
        }
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
    <title>Повторная отправка кода подтверждения</title>
</head>
<body>

<h1>Повторная отправка кода подтверждения</h1>

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

    <button type="submit">Отправить код</button>
</form>

</body>
</html>
