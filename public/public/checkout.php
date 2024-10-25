<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Проверяем, пуста ли корзина
if (count($cart) === 0) {
    echo "<p>Ваша корзина пуста.</p>";
    exit();
}

// Если пользователь авторизован, загружаем его данные
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    $stmt = $db->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Если не авторизован, используем пустые значения
    $user = ['username' => '', 'email' => ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка оформления заказа
    $total_price = 0;
    foreach ($cart as $game_id => $item) {
        // Получаем цену игры
        $stmt = $db->prepare("SELECT price FROM games WHERE id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();
        if ($game) {
            $total_price += $game['price']; // Суммируем стоимость
        }
    }

    // Сохраняем заказ в базе данных
    $stmt = $db->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'new')");
    $stmt->execute([$user_id, $total_price]);
    $order_id = $db->lastInsertId();

    // Сохраняем товары, связанные с заказом
    foreach ($cart as $game_id => $item) {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, game_id, quantity, price) VALUES (?, ?, 1, ?)");
        $stmt->execute([$order_id, $game_id, $item['price']]);
    }

    // Очищаем корзину после оформления заказа
    unset($_SESSION['cart']);

    // Перенаправляем на страницу успешного оформления заказа
    header('Location: order_success.php');
    exit();
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Оформление заказа</h1>

<p>Имя: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

<p>Общая стоимость: <?php echo $total_price; ?> тенге</p>

<form method="POST">
    <button type="submit">Подтвердить заказ</button>
</form>

<?php include '../includes/public/footer.php';  // Подключаем подвал ?>
