<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";
session_start();

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка имени пользователя и пароля
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Успешный вход
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        echo "<p>Вход успешен! Добро пожаловать, " . htmlspecialchars($user['username']) . ".</p>";
        header("Location: profile.php"); // Перенаправление на страницу профиля
        exit;
    } else {
        echo "<p>Неверное имя пользователя или пароль.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="https://gamestore.local/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Вход</h2>
    <form method="POST">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        
        <input type="submit" value="Войти" class="form-button">
        
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
</body>
</html>

