<?php
include_once "../includes/db.php"; // Подключение к базе данных

// Проверка ID игры
if (!isset($_GET['id'])) {
    die("ID игры не указан.");
}

$game_id = $_GET['id'];

// Удаление игры
$sql = "DELETE FROM games WHERE game_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);

if ($stmt->execute()) {
    echo "<p>Игра успешно удалена.</p>";
} else {
    echo "<p>Ошибка при удалении игры.</p>";
}

$conn->close();

// Перенаправление обратно на страницу управления играми
header("Location: manage_games.php");
exit;
?>
