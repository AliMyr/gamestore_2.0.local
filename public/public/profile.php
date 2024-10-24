<?php
session_start();
include '../config/config.php';

// Проверяем, авторизован ли пользователь
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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
</head>
<body>

<h1>Ваш профиль</h1>

<p>Имя пользователя: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

<h2>Ваши заказы</h2>

<?php if (count($orders) > 0): ?>
    <ul>
        <?php foreach ($orders as $order): ?>
            <li>Заказ №<?php echo $order['id']; ?> - Общая стоимость: <?php echo $order['total_price']; ?> руб. - Дата: <?php echo $order['created_at']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Вы еще не делали заказов.</p>
<?php endif; ?>

<p><a href="logout.php">Выйти</a></p>

</body>
</html>
