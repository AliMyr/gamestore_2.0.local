<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Перенаправление на страницу входа для администратора
    exit;
}
?>
<?php
include_once "../includes/db.php";

// Проверка ID пользователя
if (!isset($_GET['id'])) {
    die("ID пользователя не указан.");
}

$user_id = $_GET['id'];

// Удаление пользователя
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<p>Пользователь успешно удален.</p>";
} else {
    echo "<p>Ошибка при удалении пользователя.</p>";
}

$conn->close();

// Перенаправление обратно на страницу управления пользователями
header("Location: manage_users.php");
exit;
?>
