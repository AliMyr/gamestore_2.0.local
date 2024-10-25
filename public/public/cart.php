<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
include '../includes/public/header.php';  // Подключаем шапку

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Проверяем, пуста ли корзина
if (count($cart) === 0) {
    echo "<p>Ваша корзина пуста.</p>";
    include '../includes/public/footer.php';
    exit();
}

// Получаем товары из базы данных для отображения
$game_ids = array_keys($cart);
$placeholders = str_repeat('?,', count($game_ids) - 1) . '?';
$stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
$stmt->execute($game_ids);
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Рассчитываем общую стоимость заказа
$total_price = 0;
?>

<h1>Корзина</h1>

<form method="POST" action="update_cart.php">
    <ul>
        <?php foreach ($games as $game): ?>
            <?php
            $game_id = $game['id'];
            $quantity = $cart[$game_id]['quantity'];
            $total_price += $game['price'] * $quantity;
            ?>
            <li>
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
                <label for="quantity_<?php echo $game_id; ?>">Количество:</label>
                <input type="number" id="quantity_<?php echo $game_id; ?>" name="quantity[<?php echo $game_id; ?>]" value="<?php echo $quantity; ?>" min="1">
                <a href="remove_from_cart.php?id=<?php echo $game_id; ?>">Удалить</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <p>Общая стоимость: <?php echo $total_price; ?> тенге</p>
    <button type="submit">Обновить корзину</button>
</form>

<form method="POST" action="checkout.php">
    <button type="submit">Оформить заказ</button>
</form>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
