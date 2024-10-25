<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем список всех игр
$stmt = $db->prepare("SELECT * FROM games");
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Управление играми</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Цена</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($games as $game): ?>
        <tr>
            <td><?php echo $game['id']; ?></td>
            <td><?php echo htmlspecialchars($game['title']); ?></td>
            <td><?php echo htmlspecialchars($game['description']); ?></td>
            <td><?php echo htmlspecialchars($game['price']); ?> тенге</td>
            <td>
                <a href="edit_game.php?id=<?php echo $game['id']; ?>">Редактировать</a>
                <a href="delete_game.php?id=<?php echo $game['id']; ?>">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="add_game.php">Добавить новую игру</a>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
