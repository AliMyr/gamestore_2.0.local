<?php
include '../../config/db.php';
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в систему, чтобы видеть вашу библиотеку.");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT games.id, games.title, games.description, games.release_date FROM orders 
        JOIN games ON orders.game_id = games.id 
        WHERE orders.user_id = '$user_id'";
$result = $conn->query($sql);
?>

<h1>Моя библиотека</h1>

<table border="1">
    <tr>
        <th>Название игры</th>
        <th>Описание</th>
        <th>Дата выхода</th>
        <th>Действие</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['release_date']; ?></td>
        <td><a href="download.php?game_id=<?php echo $row['id']; ?>">Запустить/Скачать</a></td>
    </tr>
    <?php } ?>
</table>