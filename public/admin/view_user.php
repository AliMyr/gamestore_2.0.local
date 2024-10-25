<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем ID пользователя из URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Получаем информацию о пользователе
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Пользователь не найден.";
        exit();
    }

    // Получаем заказы пользователя
    $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID пользователя не указан.";
    exit();
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Заказы пользователя <?php echo htmlspecialchars($user['username']); ?></h1>

<ul>
    <?php foreach ($orders as $order): ?>
        <li>
            Заказ #<?php echo $order['id']; ?> — <?php echo $order['total_price']; ?> тенге — Статус: <?php echo htmlspecialchars($order['status']); ?>
            <a href="view_order.php?id=<?php echo $order['id']; ?>">Просмотр деталей</a>
        </li>
    <?php endforeach; ?>
</ul>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
