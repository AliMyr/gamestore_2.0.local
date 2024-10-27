<?php
session_start();
include_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "Для покупки необходимо войти в систему.";
    exit;
}

$user_id = $_SESSION['user_id'];
$game_id = $_POST['game_id'];
$amount = $_POST['amount'];

// Проверка, что игра не куплена ранее
$check_sql = "SELECT * FROM sales WHERE user_id = ? AND game_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $user_id, $game_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "Эта игра уже была куплена вами.";
} else {
    // Генерация уникального кода активации
    $activation_code = strtoupper(bin2hex(random_bytes(10))); // Код длиной 20 символов

    // Выполнение покупки с кодом активации
    $insert_sql = "INSERT INTO sales (user_id, game_id, amount, sale_date, activation_code) VALUES (?, ?, ?, NOW(), ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iids", $user_id, $game_id, $amount, $activation_code);

    if ($insert_stmt->execute()) {
        echo "Спасибо за покупку! Ваш код активации: <strong>$activation_code</strong>";
    } else {
        echo "Ошибка при выполнении покупки.";
    }
}

$conn->close();
?>
