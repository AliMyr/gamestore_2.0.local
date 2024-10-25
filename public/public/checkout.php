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
    $game_ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($game_ids) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
    $stmt->execute($game_ids);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Рассчитываем общую стоимость
    $total_price = 0;
    foreach ($games as $game) {
        $game_id = $game['id'];
        $quantity = $cart[$game_id]['quantity'];
        $total_price += $game['price'] * $quantity;
    }

    // Выводим подтверждение заказа
    echo "<h1>Подтверждение заказа</h1>";
    echo "<p>Итоговая стоимость: $total_price руб.</p>";
    echo '<form method="POST" action="process_order.php">';
    echo '<button type="submit">Подтвердить заказ и оплатить</button>';
    echo '</form>';
} else {
    echo "Корзина пуста.";
}
?>
