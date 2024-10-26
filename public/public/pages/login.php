<?php
include '../../config/db.php';
include 'public/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "Добро пожаловать, " . $user['username'] . "!";
        } else {
            echo "Неверный пароль.";
        }
    } else {
        echo "Пользователь с таким email не найден.";
    }
}
?>

<form method="post" action="">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <label>Пароль:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Войти">
</form>