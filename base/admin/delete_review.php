<?php
include_once "../includes/db.php";

// Проверка ID отзыва
if (!isset($_GET['id'])) {
    die("ID отзыва не указан.");
}

$review_id = $_GET['id'];

// Удаление отзыва
$sql = "DELETE FROM reviews WHERE review_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $review_id);

if ($stmt->execute()) {
    echo "<p>Отзыв успешно удален.</p>";
} else {
    echo "<p>Ошибка при удалении отзыва.</p>";
}

$conn->close();

// Перенаправление обратно на страницу управления отзывами
header("Location: manage_reviews.php");
exit;
?>
