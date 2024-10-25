<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Удаляем игру из корзины в сессии
    if (isset($_SESSION['cart'][$game_id])) {
        unset($_SESSION['cart'][$game_id]);
    }

    // Если пользователь авторизован, также удаляем из user_cart
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare("DELETE FROM user_cart WHERE user_id = ? AND game_id = ?");
        $stmt->execute([$user_id, $game_id]);
    }

    // Перенаправляем обратно в корзину
    header('Location: cart.php');
    exit();
} else {
    echo "ID игры не передан.";
}
?>
