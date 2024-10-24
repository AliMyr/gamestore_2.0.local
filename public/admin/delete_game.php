<?php
session_start();
include '../config/config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Получаем ID игры из URL
$game_id = $_GET['id'];

// Удаляем игру
$stmt = $db->prepare("DELETE FROM games WHERE id = ?");
$stmt->execute([$game_id]);

header('Location: admin.php');
exit();
?>
