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
    // Выполнение покупки
    $insert_sql = "INSERT INTO sales (user_id, game_id, amount) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iid", $user_id, $game_id, $amount);

    if ($insert_stmt->execute()) {
        echo "Спасибо за покупку!";
    } else {
        echo "Ошибка при выполнении покупки.";
    }
}

$conn->close();
?>
