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

<h2>Вход</h2>

<form method="POST">
    <label>Имя пользователя:</label>
    <input type="text" name="username" required><br>
    <label>Пароль:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Войти">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
