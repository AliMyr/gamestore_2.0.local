<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_id'])) {
    $user_id = $_SESSION['user_id'];
    $game_id = $_POST['game_id'];

    // Проверяем, куплена ли игра ранее
    $stmt = $db->prepare("SELECT * FROM user_games WHERE user_id = ? AND game_id = ?");
    $stmt->execute([$user_id, $game_id]);

    if ($stmt->rowCount() > 0) {
        echo "Вы уже приобрели эту игру.";
        exit();
    }

    // Получаем цену игры
    $stmt = $db->prepare("SELECT price FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        echo "Игра не найдена.";
        exit();
    }

    // Сохраняем покупку в базе данных
    $stmt = $db->prepare("INSERT INTO user_games (user_id, game_id, purchase_date) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $game_id]);

    // Перенаправляем в профиль пользователя
    header('Location: profile.php');
    exit();
} else {
    echo "Неверный запрос.";
    exit();
}
?>
