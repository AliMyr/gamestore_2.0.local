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

<form method="POST" action="checkout.php">
    <ul>
        <?php foreach ($games as $game): ?>
            <?php
            $game_id = $game['id'];
            $total_price += $game['price']; // Суммируем стоимость каждой игры
            ?>
            <li>
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
                <form method="POST" action="remove_from_cart.php" style="display:inline;">
                    <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
                    <button type="submit" style="color: red;">Удалить</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <p>Общая стоимость: <?php echo $total_price; ?> тенге</p>
    <button type="submit">Оформить заказ</button>
</form>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
