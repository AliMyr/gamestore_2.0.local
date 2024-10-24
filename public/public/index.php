<?php
session_start();
include '../config/config.php';

// Запрос на получение списка игр
$query = $db->query("SELECT * FROM games");
$games = $query->fetchAll(PDO::FETCH_ASSOC);

// Добавление игры в корзину
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Инициализируем корзину, если она ещё не существует
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Добавляем игру в корзину
    if (!in_array($game_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $game_id;
        echo "Игра добавлена в корзину!";
    } else {
        echo "Игра уже в корзине.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин игр</title>
</head>
<body>

<h1>Добро пожаловать в наш магазин игр</h1>

<?php if (count($games) > 0): ?>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <?php if ($game['image']): ?>
                    <img src="../uploads/games/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" width="150">
                <?php endif; ?>
                <p><?php echo htmlspecialchars($game['description']); ?></p>
                <p>Цена: <?php echo htmlspecialchars($game['price']); ?> руб.</p>
                <form method="POST">
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                    <button type="submit">Добавить в корзину</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Игры пока не добавлены.</p>
<?php endif; ?>

<p><a href="cart.php">Перейти в корзину</a></p>

</body>
</html>
