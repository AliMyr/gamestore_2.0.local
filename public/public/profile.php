<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, вошёл ли пользователь в систему
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Получаем информацию о пользователе
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получаем заказы пользователя
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Добро пожаловать, <?php echo htmlspecialchars($user['username']); ?>!</h1>

<h2>Ваши заказы</h2>
<ul>
    <?php foreach ($orders as $order): ?>
        <li>
            Заказ #<?php echo $order['id']; ?> — <?php echo $order['total_price']; ?> тенге — Статус: <?php echo htmlspecialchars($order['status']); ?>
        </li>
    <?php endforeach; ?>
</ul>

<a href="logout.php">Выйти</a>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
