<?php
include_once "../includes/db.php";
session_start();

// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка учетных данных в таблице admin_users
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Проверка пароля
        if (hash('sha256', $password) === $admin['password']) {
            $_SESSION['admin_logged_in'] = true; // Сохраняем состояние входа администратора
            header("Location: index.php"); // Перенаправляем на главную страницу админки
            exit;
        } else {
            $error = "Неверный пароль.";
        }
    } else {
        $error = "Неверное имя пользователя или пароль.";
    }
}
?>

<h2>Вход для администратора</h2>

<form method="POST" action="admin_login.php">
    <label for="username">Имя пользователя:</label>
    <input type="text" name="username" id="username" required>
    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required>
    <input type="submit" value="Войти">
    <?php if (isset($error)): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</form>
