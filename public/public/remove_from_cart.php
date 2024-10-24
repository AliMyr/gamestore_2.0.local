<?php
session_start();

if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Удаляем игру из корзины
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($id) use ($game_id) {
            return $id != $game_id;
        });
    }
}

header('Location: cart.php');
exit();
?>
