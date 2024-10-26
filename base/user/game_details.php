<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";
session_start();

// Получаем ID игры
if (!isset($_GET['id'])) {
    die("ID игры не указан.");
}
$game_id = $_GET['id'];

// Получение данных игры
$sql = "SELECT * FROM games WHERE game_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

// Обработка отправки отзыва
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    $insert_review_sql = "INSERT INTO reviews (game_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)";
    $insert_review_stmt = $conn->prepare($insert_review_sql);
    $insert_review_stmt->bind_param("iiis", $game_id, $user_id, $rating, $review_text);

    if ($insert_review_stmt->execute()) {
        echo "<p>Спасибо за ваш отзыв!</p>";
    } else {
        echo "<p>Ошибка при добавлении отзыва. Попробуйте снова.</p>";
    }
}
?>

<h2><?php echo htmlspecialchars($game['title']); ?></h2>
<p><?php echo htmlspecialchars($game['description']); ?></p>
<p><strong>Цена:</strong> <?php echo number_format($game['price'], 2); ?> ₸</p>

<h3>Оставить отзыв</h3>
<?php if (isset($_SESSION['user_id'])): ?>
    <form method="POST">
        <label>Рейтинг:</label>
        <select name="rating" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br>
        <label>Отзыв:</label>
        <textarea name="review_text" required></textarea><br>
        <input type="submit" value="Отправить отзыв">
    </form>
<?php else: ?>
    <p>Пожалуйста, <a href="login.php">войдите</a>, чтобы оставить отзыв.</p>
<?php endif; ?>

<?php
// Отображение отзывов
$reviews_sql = "SELECT users.username, reviews.rating, reviews.review_text, reviews.review_date 
                FROM reviews 
                JOIN users ON reviews.user_id = users.user_id 
                WHERE reviews.game_id = ? 
                ORDER BY reviews.review_date DESC";
$reviews_stmt = $conn->prepare($reviews_sql);
$reviews_stmt->bind_param("i", $game_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();

echo "<h3>Отзывы</h3>";
while ($review = $reviews_result->fetch_assoc()) {
    echo "<div class='review'>";
    echo "<p><strong>" . htmlspecialchars($review['username']) . "</strong> (" . $review['rating'] . "/5)</p>";
    echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
    echo "<p><small>" . $review['review_date'] . "</small></p>";
    echo "</div>";
}
?>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
