<?php
session_start();
include '../config/config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Получаем список игр
$query = $db->query("SELECT * FROM games");
$games = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
</head>
<body>

<h1>Административная панель</h1>

<p><a href="add_game.php">Добавить новую игру</a></p>

<h2>Список игр</h2>

<?php if (count($games) > 0): ?>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <strong><?php echo htmlspecialchars($game['title']); ?></strong> - <?php echo htmlspecialchars($game['price']); ?> руб.
                <a href="edit_game.php?id=<?php echo $game['id']; ?>">Редактировать</a> | 
                <a href="delete_game.php?id=<?php echo $game['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить эту игру?');">Удалить</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Игры пока не добавлены.</p>
<?php endif; ?>

</body>
</html>
