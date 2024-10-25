<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Если корзина не пуста
if (count($cart) > 0) {
    $game_ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($game_ids) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
    $stmt->execute($game_ids);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Отображаем товары
    foreach ($games as $game) {
        $game_id = $game['id'];
        echo "Товар: " . $game['title'] . " Количество: " . $cart[$game_id]['quantity'] . "<br>";
    }
} else {
    echo "Корзина пуста.";
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
    <form method="POST">
        <ul>
            <?php foreach ($games as $game): ?>
                <li>
                    <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                    <p>Цена: <?php echo htmlspecialchars($game['price']); ?> руб.</p>
                    <label for="quantity_<?php echo $game['id']; ?>">Количество:</label>
                    <input type="number" name="quantity" id="quantity_<?php echo $game['id']; ?>" value="<?php echo $cart[$game['id']]['quantity']; ?>" min="1">
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                    <button type="submit" name="update">Обновить количество</button>
                    <button type="submit" name="remove">Удалить из корзины</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>

    <p>Общая стоимость: <?php echo $total_price; ?> руб.</p>
    <form method="POST" action="checkout.php">
        <button type="submit">Оформить заказ</button>
    </form>
<?php else: ?>
    <p>Ваша корзина пуста.</p>
<?php endif; ?>

</body>
</html>
