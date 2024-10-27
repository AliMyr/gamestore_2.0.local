<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хеширование пароля
    $email = $_POST['email'];

    // Проверка, что имя пользователя и email уникальны
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p>Имя пользователя или email уже заняты. Пожалуйста, выберите другое.</p>";
    } else {
        // Вставка нового пользователя в базу данных
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            echo "<p>Регистрация успешна! Теперь вы можете войти.</p>";
        } else {
            echo "<p>Ошибка при регистрации. Попробуйте снова.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="https://gamestore.local/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Регистрация</h2>
    <form method="POST">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <input type="submit" value="Зарегистрироваться" class="form-button">
        
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
</body>
</html>

