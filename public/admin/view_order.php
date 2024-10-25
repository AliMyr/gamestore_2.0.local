<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../config/config.php';  // Подключение к базе данных

// Получаем информацию о заказе
$order_id = $_GET['id'];
$stmt = $db->prepare("SELECT orders.*, users.username FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Заказ не найден!";
    exit();
}

// Получаем товары, связанные с заказом
$stmt = $db->prepare("SELECT order_items.*, games.title FROM order_items JOIN games ON order_items.game_id = games.id WHERE order_items.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку админки
?>

<h1>Детали заказа #<?php echo htmlspecialchars($order['id']); ?></h1>

<p>Пользователь: <?php echo htmlspecialchars($order['username'] ? $order['username'] : 'Гость'); ?></p>
<p>Общая стоимость: <?php echo htmlspecialchars($order['total_price']); ?> тенге</p>
<p>Статус: <?php echo htmlspecialchars($order['status']); ?></p>

<h2>Товары в заказе</h2>
<table>
    <thead>
        <tr>
            <th>Название игры</th>
            <th>Количество</th>
            <th>Цена за единицу</th>
            <th>Общая стоимость</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($order_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['title']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo htmlspecialchars($item['price']); ?> тенге</td>
                <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?> тенге</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал админки
?>
