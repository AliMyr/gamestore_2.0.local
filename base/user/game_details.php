<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";
session_start();

// Проверка наличия ID игры
if (!isset($_GET['id'])) {
    die("ID игры не указан.");
}
$game_id = $_GET['id'];

// Получение всех данных об игре
$sql = "SELECT * FROM games WHERE game_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

// Проверка, что игра найдена
if (!$game) {
    die("Игра не найдена.");
}

// Проверка, оставил ли пользователь отзыв
$user_id = $_SESSION['user_id'] ?? null;
$user_review = null;
if ($user_id) {
    $review_sql = "SELECT * FROM reviews WHERE game_id = ? AND user_id = ?";
    $review_stmt = $conn->prepare($review_sql);
    $review_stmt->bind_param("ii", $game_id, $user_id);
    $review_stmt->execute();
    $user_review = $review_stmt->get_result()->fetch_assoc();
}

// Обработка отправки формы для добавления или редактирования отзыва
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_id) {
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    if ($user_review) {
        // Обновление существующего отзыва
        $update_sql = "UPDATE reviews SET rating = ?, review_text = ? WHERE review_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("isi", $rating, $review_text, $user_review['review_id']);
        $update_stmt->execute();
        echo "<p>Ваш отзыв обновлен!</p>";
    } else {
        // Добавление нового отзыва
        $insert_sql = "INSERT INTO reviews (game_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiis", $game_id, $user_id, $rating, $review_text);
        $insert_stmt->execute();
        echo "<p>Спасибо за ваш отзыв!</p>";
    }

    // Обновление данных о текущем отзыве пользователя
    $user_review = ['rating' => $rating, 'review_text' => $review_text];
}
?>

<div class="game-details">
    <h2><?php echo htmlspecialchars($game['title']); ?></h2>

    <?php if ($game['cover_image']): ?>
        <img src="<?php echo htmlspecialchars($game['cover_image']); ?>" alt="Обложка игры" class="game-cover">
    <?php endif; ?>

    <p><?php echo htmlspecialchars($game['description']); ?></p>

    <?php if (!empty($game['developer'])): ?>
        <p><strong>Разработчик:</strong> <?php echo htmlspecialchars($game['developer']); ?></p>
    <?php endif; ?>

    <?php if (!empty($game['publisher'])): ?>
        <p><strong>Издатель:</strong> <?php echo htmlspecialchars($game['publisher']); ?></p>
    <?php endif; ?>

    <?php if (!empty($game['release_date'])): ?>
        <p><strong>Дата выпуска:</strong> <?php echo htmlspecialchars($game['release_date']); ?></p>
    <?php endif; ?>

    <?php if (!empty($game['age_rating'])): ?>
        <p><strong>Возрастной рейтинг:</strong> <?php echo htmlspecialchars($game['age_rating']); ?></p>
    <?php endif; ?>

    <?php if (!empty($game['genre'])): ?>
        <p><strong>Жанр:</strong> <?php echo htmlspecialchars($game['genre']); ?></p>
    <?php endif; ?>

    <?php if (!empty($game['system_requirements'])): ?>
        <p><strong>Системные требования:</strong> <?php echo nl2br(htmlspecialchars($game['system_requirements'])); ?></p>
    <?php endif; ?>

    <p class="price"><strong>Цена:</strong> <?php echo number_format($game['price'], 2); ?> ₸</p>

    <?php if (!empty($game['trailer_url'])): ?>
        <h3>Трейлер</h3>
        <iframe width="560" height="315" src="<?php echo htmlspecialchars($game['trailer_url']); ?>" frameborder="0" allowfullscreen></iframe>
    <?php endif; ?>
</div>

<h3 class="avg-rating">Средний рейтинг</h3>
<?php
$rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE game_id = ?";
$rating_stmt = $conn->prepare($rating_sql);
$rating_stmt->bind_param("i", $game_id);
$rating_stmt->execute();
$avg_rating = $rating_stmt->get_result()->fetch_assoc()['avg_rating'];

echo "<p><strong>Средний рейтинг:</strong> " . ($avg_rating ? round($avg_rating, 1) . " / 5" : "Нет рейтинга") . "</p>";
?>

<div class="review-section">
    <h3>Ваш отзыв</h3>
    <?php if ($user_review): ?>
        <!-- Форма редактирования отзыва -->
        <form method="POST">
            <label>Рейтинг:</label>
            <select name="rating" required>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if ($user_review['rating'] == $i) echo 'selected'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <label>Отзыв:</label>
            <textarea name="review_text" required><?php echo htmlspecialchars($user_review['review_text']); ?></textarea>
            <input type="submit" value="Обновить отзыв">
            <?php if (isset($user_review['review_id'])): ?>
                <a href="delete_review.php?review_id=<?php echo $user_review['review_id']; ?>&game_id=<?php echo $game_id; ?>" class="delete-review" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?');">Удалить отзыв</a>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <!-- Форма добавления нового отзыва -->
        <form method="POST">
            <label>Рейтинг:</label>
            <select name="rating" required>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <label>Отзыв:</label>
            <textarea name="review_text" required></textarea>
            <input type="submit" value="Оставить отзыв">
        </form>
    <?php endif; ?>
</div>

<div class="other-reviews">
    <h3>Отзывы других пользователей</h3>
    <?php
    // Отображение всех отзывов, кроме отзыва текущего пользователя
    $reviews_sql = "SELECT users.username, reviews.rating, reviews.review_text, reviews.review_date 
                    FROM reviews 
                    JOIN users ON reviews.user_id = users.user_id 
                    WHERE reviews.game_id = ? AND reviews.user_id != ? 
                    ORDER BY reviews.review_date DESC";
    $reviews_stmt = $conn->prepare($reviews_sql);
    $reviews_stmt->bind_param("ii", $game_id, $user_id);
    $reviews_stmt->execute();
    $reviews_result = $reviews_stmt->get_result();

    while ($review = $reviews_result->fetch_assoc()) {
        echo "<div class='review'>";
        echo "<p><strong>" . htmlspecialchars($review['username']) . "</strong> (" . $review['rating'] . "/5)</p>";
        echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
        echo "<p><small>" . $review['review_date'] . "</small></p>";
        echo "</div>";
    }
    ?>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
