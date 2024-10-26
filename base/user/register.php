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

<h2>Регистрация</h2>

<form method="POST">
    <label>Имя пользователя:</label>
    <input type="text" name="username" required><br>
    <label>Пароль:</label>
    <input type="password" name="password" required><br>
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <input type="submit" value="Зарегистрироваться">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
