<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем ID заказа из URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Получаем данные о заказе
    $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Заказ не найден.";
        exit();
    }

    // Получаем товары, связанные с этим заказом
    $stmt = $db->prepare("SELECT oi.*, g.title FROM order_items oi JOIN games g ON oi.game_id = g.id WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID заказа не указан.";
    exit();
}

// Обработка изменения статуса заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    // Перенаправляем обратно к списку заказов после обновления статуса
    header('Location: manage_orders.php');
    exit();
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Детали заказа #<?php echo $order['id']; ?></h1>

<p>Пользователь ID: <?php echo $order['user_id']; ?></p>
<p>Общая стоимость: <?php echo $order['total_price']; ?> тенге</p>
<p>Статус заказа: <?php echo htmlspecialchars($order['status']); ?></p>

<h2>Товары в заказе</h2>
<ul>
    <?php foreach ($order_items as $item): ?>
        <li>
            <?php echo htmlspecialchars($item['title']); ?> — <?php echo $item['quantity']; ?> шт.
            по <?php echo $item['price']; ?> тенге за штуку.
        </li>
    <?php endforeach; ?>
</ul>

<h2>Изменить статус заказа</h2>
<form method="POST">
    <select name="status">
        <option value="new" <?php echo $order['status'] === 'new' ? 'selected' : ''; ?>>Новый</option>
        <option value="in_progress" <?php echo $order['status'] === 'in_progress' ? 'selected' : ''; ?>>В обработке</option>
        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Выполнен</option>
        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Отменён</option>
    </select>
    <button type="submit">Обновить статус</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
