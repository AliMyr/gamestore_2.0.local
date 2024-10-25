<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Если пользователь авторизован, сохраняем корзину в базе данных
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Проверяем, есть ли уже эта игра в корзине пользователя
        $stmt = $db->prepare("SELECT * FROM user_cart WHERE user_id = ? AND game_id = ?");
        $stmt->execute([$user_id, $game_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
            // Если игра уже есть в корзине, обновляем количество
            $stmt = $db->prepare("UPDATE user_cart SET quantity = quantity + 1 WHERE user_id = ? AND game_id = ?");
            $stmt->execute([$user_id, $game_id]);
        } else {
            // Если игры нет в корзине, добавляем её
            $stmt = $db->prepare("INSERT INTO user_cart (user_id, game_id, quantity) VALUES (?, ?, 1)");
            $stmt->execute([$user_id, $game_id]);
        }
    } else {
        // Инициализируем корзину для неавторизованных пользователей в сессии
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Если игра уже есть в корзине, увеличиваем количество
        if (isset($_SESSION['cart'][$game_id])) {
            $_SESSION['cart'][$game_id]['quantity']++;
        } else {
            // Если игры нет в корзине, добавляем её с количеством 1
            $_SESSION['cart'][$game_id] = ['quantity' => 1];
        }
    }

    // Перенаправляем пользователя на страницу корзины
    header('Location: cart.php');
    exit();
}
?>
