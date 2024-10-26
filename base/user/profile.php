<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    echo "<p>Пожалуйста, войдите в систему для доступа к профилю.</p>";
    header("Location: login.php");
    exit;
}

// Получение данных пользователя
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Проверка, существует ли пользователь
if (!$user) {
    // Если пользователя не существует, удаляем сессию и перенаправляем на страницу входа
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<h2>Профиль пользователя</h2>

<p><strong>Имя пользователя:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
<p><strong>Дата регистрации:</strong> <?php echo htmlspecialchars($user['registration_date']); ?></p>

<a href="logout.php">Выйти</a>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
