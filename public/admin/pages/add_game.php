<?php
include '../../config/db.php';
include '../includes/header.php';
session_start();

// Проверка авторизации администратора
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) { // Предположим, что пользователь с ID 1 является администратором
    die("Доступ запрещен. Только администраторы могут добавлять игры.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $release_date = $_POST['release_date'];

    $sql = "INSERT INTO games (title, description, price, release_date) VALUES ('$title', '$description', '$price', '$release_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Игра успешно добавлена!";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="post" action="">
    <label>Название игры:</label>
    <input type="text" name="title" required><br>
    <label>Описание:</label>
    <textarea name="description" required></textarea><br>
    <label>Цена (в тенге):</label>
    <input type="text" name="price" required><br>
    <label>Дата выхода:</label>
    <input type="date" name="release_date" required><br>
    <input type="submit" value="Добавить игру">
</form>