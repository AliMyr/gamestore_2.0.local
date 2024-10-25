<?php
session_start();

// Получаем ID игры для удаления из корзины
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Если товар есть в корзине, удаляем его
    if (isset($_SESSION['cart'][$game_id])) {
        unset($_SESSION['cart'][$game_id]);
    }
}

// Перенаправляем обратно на страницу корзины
header('Location: cart.php');
exit();
