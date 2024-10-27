<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";
session_start();

// Проверка прав администратора
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Получение уникальных пользователей для фильтра
$user_sql = "SELECT DISTINCT username FROM users";
$user_result = $conn->query($user_sql);

// Обработка фильтров
$date_filter = $_GET['date'] ?? '';
$user_filter = $_GET['user'] ?? '';

$sales_sql = "
    SELECT sales.sale_id, sales.sale_date, sales.amount, users.username, games.title 
    FROM sales
    JOIN users ON sales.user_id = users.user_id
    JOIN games ON sales.game_id = games.game_id
    WHERE 1=1";

if ($date_filter) {
    $sales_sql .= " AND DATE(sales.sale_date) = '" . $conn->real_escape_string($date_filter) . "'";
}

if ($user_filter) {
    $sales_sql .= " AND users.username = '" . $conn->real_escape_string($user_filter) . "'";
}

$sales_sql .= " ORDER BY sales.sale_date DESC";
$sales_result = $conn->query($sales_sql);
?>

<h2>Отчет о продажах</h2>

<!-- Форма для фильтров -->
<form method="GET" action="sales_report.php">
    <label for="date">Дата:</label>
    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($date_filter); ?>">

    <label for="user">Пользователь:</label>
    <select name="user" id="user">
        <option value="">Все пользователи</option>
        <?php while ($user = $user_result->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($user['username']); ?>" <?php if ($user_filter == $user['username']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($user['username']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="submit" value="Применить фильтр">
</form>

<!-- Таблица отчетов о продажах -->
<table>
    <tr>
        <th>Дата продажи</th>
        <th>Пользователь</th>
        <th>Игра</th>
        <th>Сумма</th>
    </tr>
    <?php while ($sale = $sales_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
            <td><?php echo htmlspecialchars($sale['username']); ?></td>
            <td><?php echo htmlspecialchars($sale['title']); ?></td>
            <td><?php echo number_format($sale['amount'], 2); ?> ₸</td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Экспорт данных в CSV -->
<form method="POST" action="export_sales.php">
    <input type="hidden" name="date" value="<?php echo htmlspecialchars($date_filter); ?>">
    <input type="hidden" name="user" value="<?php echo htmlspecialchars($user_filter); ?>">
    <input type="submit" value="Скачать отчет в формате CSV">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
