<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем ID игры из URL
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Удаляем игру из базы данных
    $stmt = $db->prepare("DELETE FROM games WHERE id = ?");
    $stmt->execute([$game_id]);

    // Перенаправляем на страницу управления играми
    header('Location: manage_games.php');
    exit();
} else {
    echo "ID игры не указан.";
}
?>
