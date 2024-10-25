<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];
    $quantity = $_POST['quantity'];

    // Проверяем, авторизован ли пользователь
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Проверяем, есть ли уже товар в корзине этого пользователя
        $stmt = $db->prepare("SELECT * FROM user_cart WHERE user_id = ? AND game_id = ?");
        $stmt->execute([$user_id, $game_id]);
        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_item) {
            // Если товар уже есть, обновляем количество
            $new_quantity = $existing_item['quantity'] + $quantity;
            $stmt = $db->prepare("UPDATE user_cart SET quantity = ? WHERE user_id = ? AND game_id = ?");
            $stmt->execute([$new_quantity, $user_id, $game_id]);
        } else {
            // Если товара нет, добавляем новый
            $stmt = $db->prepare("INSERT INTO user_cart (user_id, game_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $game_id, $quantity]);
        }
    } else {
        // Если пользователь не авторизован, добавляем в сессию
        if (isset($_SESSION['cart'][$game_id])) {
            $_SESSION['cart'][$game_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$game_id] = ['quantity' => $quantity];
        }
    }

    header("Location: cart.php");
    exit();
}
?>
