<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Обновляем статус заказа в базе данных
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    // Перенаправляем обратно на страницу просмотра заказа
    header("Location: view_order.php?id=$order_id");
    exit();
}
?>
