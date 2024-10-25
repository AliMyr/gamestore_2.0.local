<?php
session_start();  // Начинаем сессию для сохранения корзины
include '../config/config.php';  // Подключение к базе данных

// Проверяем, отправлена ли форма с данными
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];
    $quantity = $_POST['quantity'];

    // Если корзины ещё нет, создаем пустой массив для корзины
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Если товар уже есть в корзине, обновляем количество
    if (isset($_SESSION['cart'][$game_id])) {
        $_SESSION['cart'][$game_id]['quantity'] += $quantity;
    } else {
        // Добавляем новый товар в корзину
        $_SESSION['cart'][$game_id] = ['quantity' => $quantity];
    }

    // Перенаправляем на страницу корзины
    header('Location: cart.php');
    exit();
}
