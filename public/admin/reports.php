<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Даты по умолчанию (последний месяц)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Получаем количество заказов за период
$stmt = $db->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE created_at BETWEEN ? AND ?");
$stmt->execute([$start_date, $end_date]);
$total_orders = $stmt->fetchColumn();

// Получаем доход за период
$stmt = $db->prepare("SELECT SUM(total_price) as total_income FROM orders WHERE status = 'completed' AND created_at BETWEEN ? AND ?");
$stmt->execute([$start_date, $end_date]);
$total_income = $stmt->fetchColumn();
$total_income = $total_income ? $total_income : 0;  // Устанавливаем значение 0, если данных нет

// Получаем количество новых пользователей за период
$stmt = $db->prepare("SELECT COUNT(*) as total_users FROM users WHERE created_at BETWEEN ? AND ?");
$stmt->execute([$start_date, $end_date]);
$total_users = $stmt->fetchColumn();

// Получаем самые популярные игры за период
$stmt = $db->prepare("
    SELECT games.title, SUM(order_items.quantity) as total_sales
    FROM order_items
    JOIN games ON order_items.game_id = games.id
    JOIN orders ON order_items.order_id = orders.id
    WHERE orders.created_at BETWEEN ? AND ?
    GROUP BY games.id
    ORDER BY total_sales DESC
    LIMIT 5
");
$stmt->execute([$start_date, $end_date]);
$top_games = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Отчетность за период</h1>

<form method="GET">
    <label for="start_date">Дата начала:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">

    <label for="end_date">Дата окончания:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">

    <button type="submit">Обновить отчет</button>
</form>

<h2>Общие показатели</h2>
<ul>
    <li>Общее количество заказов: <?php echo $total_orders ? $total_orders : 0; ?></li>
    <li>Общий доход (выполненные заказы): <?php echo number_format($total_income, 2); ?> тенге</li>
    <li>Количество новых пользователей: <?php echo $total_users ? $total_users : 0; ?></li>
</ul>

<h2>Самые популярные игры</h2>
<table>
    <thead>
        <tr>
            <th>Название игры</th>
            <th>Продано копий</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($top_games)): ?>
            <?php foreach ($top_games as $game): ?>
                <tr>
                    <td><?php echo htmlspecialchars($game['title']); ?></td>
                    <td><?php echo htmlspecialchars($game['total_sales']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Нет данных о продажах игр за выбранный период.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
