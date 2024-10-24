<?php
session_start();
include '../config/config.php';

// Проверяем, есть ли игры в корзине
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$user_id = $_SESSION['user_id'] ?? null;

if (count($cart) > 0 && $user_id) {
    // Получаем игры из базы данных по ID
    $placeholders = str_repeat('?,', count($cart) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
    $stmt->execute($cart);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Рассчитываем общую стоимость
    $total_price = array_sum(array_column($games, 'price'));

    // Вставляем заказ в таблицу orders
    $stmt = $db->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->execute([$user_id, $total_price]);
    $order_id = $db->lastInsertId();

    // Вставляем позиции заказа в таблицу order_items
    foreach ($games as $game) {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, game_id, price) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $game['id'], $game['price']]);
    }

    // Очищаем корзину
    unset($_SESSION['cart']);

    echo "Ваш заказ успешно оформлен!";
} else {
    echo "Корзина пуста или вы не авторизованы.";
}
?>
