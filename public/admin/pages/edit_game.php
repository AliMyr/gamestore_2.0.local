<?php
include '../../config/db.php';
include '../includes/header.php';
session_start();

// Проверка авторизации администратора
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Доступ запрещен. Только администраторы могут редактировать игры.");
}

// Получение информации об игре для редактирования
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM games WHERE id='$id'";
    $result = $conn->query($sql);
    $game = $result->fetch_assoc();
}

// Обновление информации об игре
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $release_date = $_POST['release_date'];

    $sql = "UPDATE games SET title='$title', description='$description', price='$price', release_date='$release_date' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Игра успешно обновлена!";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="post" action="">
    <label>Название игры:</label>
    <input type="text" name="title" value="<?php echo $game['title']; ?>" required><br>
    <label>Описание:</label>
    <textarea name="description" required><?php echo $game['description']; ?></textarea><br>
    <label>Цена (в тенге):</label>
    <input type="text" name="price" value="<?php echo $game['price']; ?>" required><br>
    <label>Дата выхода:</label>
    <input type="date" name="release_date" value="<?php echo $game['release_date']; ?>" required><br>
    <input type="submit" value="Обновить игру">
</form>