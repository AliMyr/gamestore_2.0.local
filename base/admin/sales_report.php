<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Получение общего количества продаж
$total_sales_query = "SELECT COUNT(*) AS total_sales FROM sales";
$total_sales_result = $conn->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

// Получение общей суммы продаж
$total_amount_query = "SELECT SUM(amount) AS total_amount FROM sales";
$total_amount_result = $conn->query($total_amount_query);
$total_amount = $total_amount_result->fetch_assoc()['total_amount'];

// Получение последних продаж
$recent_sales_query = "SELECT sales.sale_date, users.username, games.title, sales.amount 
                       FROM sales 
                       JOIN users ON sales.user_id = users.user_id 
                       JOIN games ON sales.game_id = games.game_id 
                       ORDER BY sales.sale_date DESC 
                       LIMIT 10";
$recent_sales_result = $conn->query($recent_sales_query);
?>

<h2>Отчет о продажах</h2>

<div class="sales-report">
    <p><strong>Общее количество продаж:</strong> <?php echo $total_sales; ?></p>
    <p><strong>Общая сумма продаж:</strong> <?php echo number_format($total_amount, 2); ?> ₸</p>

    <h3>Последние продажи</h3>
    <table>
        <tr>
            <th>Дата</th>
            <th>Пользователь</th>
            <th>Игра</th>
            <th>Сумма</th>
        </tr>
        <?php while ($sale = $recent_sales_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $sale['sale_date']; ?></td>
                <td><?php echo htmlspecialchars($sale['username']); ?></td>
                <td><?php echo htmlspecialchars($sale['title']); ?></td>
                <td><?php echo number_format($sale['amount'], 2); ?> ₸</td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
