<?php
session_start();
include '../config/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $verification_code = trim($_POST['verification_code']);

    // Проверяем, существует ли пользователь с указанным email и кодом
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ?");
    $stmt->execute([$email, $verification_code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Обновляем статус подтверждения email
        $stmt = $db->prepare("UPDATE users SET email_verified = 1 WHERE email = ?");
        $stmt->execute([$email]);

        echo "Ваш email успешно подтверждён!";
        header('Location: login.php');
        exit();
    } else {
        $errors[] = "Неверный код подтверждения или email.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение email</title>
</head>
<body>

<h1>Подтверждение email</h1>

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

    <label for="verification_code">Введите код подтверждения:</label>
    <input type="text" name="verification_code" id="verification_code" required><br>

    <button type="submit">Подтвердить</button>
</form>

</body>
</html>
