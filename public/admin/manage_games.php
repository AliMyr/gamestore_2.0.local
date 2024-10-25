<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../config/config.php';  // Подключение к базе данных

// Логика удаления игры
if (isset($_GET['delete'])) {
    $game_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    header('Location: manage_games.php');  // Перенаправляем на ту же страницу после удаления
    exit();
}

// Получаем список всех игр
$stmt = $db->query("SELECT * FROM games ORDER BY created_at DESC");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку админки
?>

<h1>Управление играми</h1>

<a href="add_game.php">Добавить новую игру</a>

<table>
    <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Цена</th>
            <th>Изображение</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($games as $game): ?>
            <tr>
                <td><?php echo htmlspecialchars($game['title']); ?></td>
                <td><?php echo htmlspecialchars($game['description']); ?></td>
                <td><?php echo htmlspecialchars($game['price']); ?> тенге</td>
                <td><img src="../uploads/<?php echo htmlspecialchars($game['image']); ?>" width="100" alt="Изображение"></td>
                <td>
                    <a href="edit_game.php?id=<?php echo $game['id']; ?>">Редактировать</a>
                    <a href="manage_games.php?delete=<?php echo $game['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить эту игру?');">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал админки
?>
