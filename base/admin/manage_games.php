<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Перенаправление на страницу входа для администратора
    exit;
}
?>
<?php
include_once "../includes/db.php"; // Подключение к базе данных
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Получаем список игр
$sql = "SELECT * FROM games";
$result = $conn->query($sql);
?>

<h2>Управление играми</h2>

<div class="game-management">
    <table>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Цена</th>
            <th>Дата выпуска</th>
            <th>Жанр</th>
            <th>Действия</th>
        </tr>
        <?php while ($game = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($game['title']); ?></td>
                <td><?php echo htmlspecialchars($game['description']); ?></td>
                <td><?php echo number_format($game['price'], 2); ?> ₸</td>
                <td><?php echo htmlspecialchars($game['release_date']); ?></td>
                <td><?php echo htmlspecialchars($game['genre']); ?></td>
                <td>
                    <a href="edit_game.php?id=<?php echo $game['game_id']; ?>">Редактировать</a> |
                    <a href="delete_game.php?id=<?php echo $game['game_id']; ?>" onclick="return confirm('Вы уверены?');">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
