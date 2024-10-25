<?php
session_start();
include '../config/config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (count($cart) > 0) {
    // Создаём заказ
    $stmt = $db->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->execute([$user_id, $total_price]);
    $order_id = $db->lastInsertId();

    // Сохраняем товары заказа
    foreach ($games as $game) {
        $game_id = $game['id'];
        $quantity = $cart[$game_id]['quantity'];
        $stmt = $db->prepare("INSERT INTO order_items (order_id, game_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $game_id, $quantity]);
    }

    // Очищаем корзину
    unset($_SESSION['cart']);
    echo "Ваш заказ успешно оформлен!";
} else {
    echo "Корзина пуста.";
}
?>
