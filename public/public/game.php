<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, передан ли id игры
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Получаем информацию об игре
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        echo "Игра не найдена.";
        exit();
    }
} else {
    echo "ID игры не передан.";
    exit();
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1><?php echo htmlspecialchars($game['title']); ?></h1>
<img src="<?php echo htmlspecialchars($game['image']); ?>" alt="Изображение игры" style="max-width: 400px;">
<p><?php echo htmlspecialchars($game['description']); ?></p>
<p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
<p>Жанр: <?php echo htmlspecialchars($game['genre']); ?></p>
<p>Дата выпуска: <?php echo htmlspecialchars($game['release_date']); ?></p>

<?php if (isset($_SESSION['user_id'])): ?>
    <form method="POST" action="buy_game.php">
        <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
        <button type="submit">Купить игру</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Войдите в систему</a>, чтобы купить эту игру.</p>
<?php endif; ?>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
