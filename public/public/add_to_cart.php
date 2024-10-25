<?php
session_start();
include '../config/config.php';  // Подключение базы данных

// Проверяем, что был отправлен запрос методом POST и есть game_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Проверяем, инициализирована ли корзина в сессии, если нет — создаём
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Если товар уже есть в корзине, увеличиваем его количество
    if (isset($_SESSION['cart'][$game_id])) {
        $_SESSION['cart'][$game_id]['quantity'] += 1;
    } else {
        // Если товара нет в корзине, добавляем его с количеством 1
        $_SESSION['cart'][$game_id] = ['quantity' => 1];
    }

    // Выводим сообщение для пользователя (для теста)
    echo "Товар успешно добавлен в корзину.";
}
?>
