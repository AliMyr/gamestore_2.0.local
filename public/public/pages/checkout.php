<?php
include '../../config/db.php';
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в систему, чтобы оформить заказ.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $game_id = $_POST['game_id'];
    
    // Проверка, куплена ли уже игра
    $sql_check = "SELECT * FROM orders WHERE user_id='$user_id' AND game_id='$game_id'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        die("Вы уже купили эту игру.");
    }
    
    // Простая имитация успешной оплаты
    $is_paid = true; // Здесь в реальном проекте будет логика оплаты

    if ($is_paid) {
        // Добавление заказа
        $sql = "INSERT INTO orders (user_id, game_id, order_date) VALUES ('$user_id', '$game_id', NOW())";
        if ($conn->query($sql) === TRUE) {
            echo "Спасибо за покупку! Игра добавлена в вашу библиотеку.";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } else {
        echo "Ошибка при оплате. Попробуйте снова.";
    }
}

// Отображение доступных игр для покупки
$sql = "SELECT * FROM games";
$result = $conn->query($sql);
?>

<h1>Оформление заказа</h1>

<form method="post" action="">
    <label>Выберите игру:</label>
    <select name="game_id" required>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?> - <?php echo $row['price']; ?> KZT</option>
        <?php } ?>
    </select><br><br>
    <input type="submit" value="Оформить заказ">
</form>