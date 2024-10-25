<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Если пользователь авторизован, загружаем корзину из базы данных
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT * FROM user_cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as $item) {
        $_SESSION['cart'][$item['game_id']] = ['quantity' => $item['quantity']];
    }
}

// Если корзина не пуста
if (count($cart) > 0) {
    $game_ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($game_ids) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
    $stmt->execute($game_ids);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Рассчитываем общую стоимость
    $total_price = 0;
    foreach ($games as $game) {
        $game_id = $game['id'];
        $quantity = $cart[$game_id]['quantity'];
        $total_price += $game['price'] * $quantity;
    }
} else {
    $games = [];
    $total_price = 0;
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Корзина</h1>

<?php if (count($games) > 0): ?>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
                <p>Количество: <?php echo $cart[$game['id']]['quantity']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
    <p>Общая стоимость: <?php echo $total_price; ?> тенге</p>

    <form method="POST" action="checkout.php">
        <button type="submit">Оформить заказ</button>
    </form>
<?php else: ?>
    <p>Ваша корзина пуста.</p>
<?php endif; ?>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
