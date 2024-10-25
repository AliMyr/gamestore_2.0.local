<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['id'];

// Получаем информацию о заказе
$stmt = $db->prepare("SELECT orders.*, users.username FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<p>Заказ не найден.</p>";
    exit();
}

// Получаем товары, связанные с заказом
$stmt = $db->prepare("SELECT order_items.*, games.title FROM order_items JOIN games ON order_items.game_id = games.id WHERE order_items.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

include '../includes/admin/header.php';  // Подключаем шапку для админки
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

<form method="POST" action="change_status.php">
    <label for="status">Изменить статус заказа:</label>
    <select name="status" id="status">
        <option value="new" <?php if ($order['status'] === 'new') echo 'selected'; ?>>Новый</option>
        <option value="completed" <?php if ($order['status'] === 'completed') echo 'selected'; ?>>Выполнен</option>
        <option value="canceled" <?php if ($order['status'] === 'canceled') echo 'selected'; ?>>Отменён</option>
    </select>
    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
    <button type="submit">Обновить статус</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
