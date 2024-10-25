<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Получаем все игры, купленные пользователем
$stmt = $db->prepare("
    SELECT games.* 
    FROM user_games 
    JOIN games ON user_games.game_id = games.id 
    WHERE user_games.user_id = ?
");
$stmt->execute([$user_id]);
$user_games = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Профиль пользователя: <?php echo htmlspecialchars($username); ?></h1>
<h2>Ваши игры</h2>

<?php if (count($user_games) > 0): ?>
    <ul>
        <?php foreach ($user_games as $game): ?>
            <li>
                <h3><?php echo htmlspecialchars($game['title']); ?></h3>
                <img src="<?php echo htmlspecialchars($game['image']); ?>" alt="Изображение игры" style="max-width: 200px;">
                <p><?php echo htmlspecialchars($game['description']); ?></p>
                <p>Дата покупки: <?php echo htmlspecialchars($game['purchase_date']); ?></p>
                <a href="play_game.php?id=<?php echo $game['id']; ?>">Играть</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>У вас пока нет купленных игр.</p>
<?php endif; ?>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
