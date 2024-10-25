<?php
session_start();

// Если администратор уже авторизован, перенаправляем его на панель
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');  // Перенаправляем на панель администратора
    exit();
}

include '../config/config.php';  // Подключение к базе данных

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Примитивная проверка логина и пароля (логика входа)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');  // Перенаправляем на панель администратора после входа
        exit();
    } else {
        $errors[] = "Неправильный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админку</title>
</head>
<body>
<h1>Вход в админку</h1>

<?php if (!empty($errors)): ?>
    <p><?php echo $errors[0]; ?></p>
<?php endif; ?>

<form method="POST">
    <label for="username">Логин:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Войти</button>
</form>

</body>
</html>
