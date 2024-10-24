<?php
session_start();
include '../config/config.php';

// Проверяем, есть ли игры в корзине
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (count($cart) > 0) {
    // Получаем игры из базы данных по ID, сохраненным в сессии
    $placeholders = str_repeat('?,', count($cart) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
    $stmt->execute($cart);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $games = [];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина покупок</title>
</head>
<body>

<h1>Корзина покупок</h1>

<?php if (count($games) > 0): ?>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <p><?php echo htmlspecialchars($game['price']); ?> руб.</p>
                <form method="POST" action="remove_from_cart.php">
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                    <button type="submit">Удалить из корзины</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <p>Общая стоимость: <?php echo array_sum(array_column($games, 'price')); ?> руб.</p>

    <form method="POST" action="checkout.php">
        <button type="submit">Оформить заказ</button>
    </form>

<?php else: ?>
    <p>Корзина пуста.</p>
<?php endif; ?>

</body>
</html>
