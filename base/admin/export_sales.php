<?php
include_once "../includes/db.php";
session_start();

// Проверка прав администратора
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Получение фильтров из запроса
$date_filter = $_POST['date'] ?? '';
$user_filter = $_POST['user'] ?? '';

// Запрос для выборки продаж с учетом фильтров
$sales_sql = "
    SELECT sales.sale_date, users.username, games.title, sales.amount 
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

// Установка заголовков для скачивания файла
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="sales_report.csv"');

// Открываем файл в режиме записи в поток
$output = fopen('php://output', 'w');

// Добавляем BOM для Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Записываем заголовки в CSV (разделитель - запятая)
fputcsv($output, array('Дата продажи', 'Пользователь', 'Игра', 'Сумма'));

// Записываем данные о продажах в CSV
while ($sale = $sales_result->fetch_assoc()) {
    // Преобразуем формат суммы, чтобы отображалось корректно
    $sale['amount'] = number_format($sale['amount'], 2);
    fputcsv($output, $sale);
}

// Закрываем поток
fclose($output);
$conn->close();
exit;
?>
