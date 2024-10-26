<?php
include '../../config/db.php';
include '../includes/header.php';
session_start();

// Проверка авторизации администратора
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Доступ запрещен. Только администраторы могут управлять играми.");
}

// Удаление игры
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM games WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Игра успешно удалена!";
    } else {
        echo "Ошибка при удалении: " . $conn->error;
    }
}

// Отображение списка игр для управления
$sql = "SELECT * FROM games";
$result = $conn->query($sql);
?>

<h1>Управление играми</h1>
<a href="add_game.php">Добавить новую игру</a><br><br>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Цена</th>
        <th>Дата выхода</th>
        <th>Действия</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['price']; ?> KZT</td>
        <td><?php echo $row['release_date']; ?></td>
        <td>
            <a href="edit_game.php?id=<?php echo $row['id']; ?>">Редактировать</a> |
            <a href="manage_games.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить эту игру?');">Удалить</a>
        </td>
    </tr>
    <?php } ?>
</table>