<?php
include_once "../includes/db.php";
session_start();

// Проверка наличия ID отзыва и ID игры
if (!isset($_GET['review_id']) || !isset($_GET['game_id'])) {
    die("Ошибка: не указан ID отзыва или игры.");
}

$review_id = $_GET['review_id'];
$game_id = $_GET['game_id'];
$user_id = $_SESSION['user_id'] ?? null;

// Проверка, что пользователь авторизован
if (!$user_id) {
    die("Ошибка: пользователь не авторизован.");
}

// Удаление отзыва, если он принадлежит текущему пользователю
$delete_sql = "DELETE FROM reviews WHERE review_id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("ii", $review_id, $user_id);
$delete_stmt->execute();

// Перенаправление обратно на страницу игры
header("Location: game_details.php?id=$game_id");
exit;
