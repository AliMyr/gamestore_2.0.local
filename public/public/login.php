<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Проверяем, что email и пароль заполнены
    if (empty($email)) {
        $errors[] = "Email не может быть пустым.";
    }
    if (empty($password)) {
        $errors[] = "Пароль не может быть пустым.";
    }

    // Если ошибок нет, проверяем логин и пароль
    if (empty($errors)) {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Успешная аутентификация
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];  // Устанавливаем имя пользователя в сессии
            $_SESSION['email'] = $user['email'];        // Устанавливаем email пользователя в сессии

            // Перенаправляем на главную страницу или профиль
            header('Location: profile.php');
            exit();
        } else {
            $errors[] = "Неверный email или пароль.";
        }
    }
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Вход в систему</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
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

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
