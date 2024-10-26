<?php
include '../../config/db.php';
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в систему, чтобы оставить отзыв.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $game_id = $_POST['game_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $sql = "INSERT INTO reviews (user_id, game_id, rating, review, created_at) VALUES ('$user_id', '$game_id', '$rating', '$review', NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "Спасибо за ваш отзыв!";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}

// Отображение формы для добавления отзыва
?>

<form method="post" action="">
    <label>Игра:</label>
    <select name="game_id" required>
        <?php
        $sql_games = "SELECT * FROM games";
        $result_games = $conn->query($sql_games);
        while ($row = $result_games->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
        }
        ?>
    </select><br><br>
    <label>Рейтинг (1-5):</label>
    <input type="number" name="rating" min="1" max="5" required><br>
    <label>Отзыв:</label><br>
    <textarea name="review" rows="4" cols="50" required></textarea><br><br>
    <input type="submit" value="Оставить отзыв">
</form>