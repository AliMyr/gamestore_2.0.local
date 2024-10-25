<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Получаем заказы пользователя
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Профиль пользователя</h1>

<p>Имя пользователя: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

<h2>История покупок</h2>

<?php if (count($orders) > 0): ?>
    <ul>
        <?php foreach ($orders as $order): ?>
            <li>Заказ #<?php echo htmlspecialchars($order['id']); ?> - <?php echo htmlspecialchars($order['total_price']); ?> тенге (<?php echo htmlspecialchars($order['status']); ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>У вас нет заказов.</p>
<?php endif; ?>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
