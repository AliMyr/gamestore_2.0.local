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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {
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

    <?php if ($user_id): ?>
        <form id="purchase-form">
            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($game['price']); ?>">
            <input type="submit" value="Купить" class="button">
        </form>
        <div id="purchase-message"></div>
    <?php else: ?>
        <p><a href="login.php">Войдите</a> для покупки этой игры.</p>
    <?php endif; ?>
</div>

<script>
document.getElementById('purchase-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('purchase.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById('purchase-message').innerHTML = result;
    })
    .catch(error => {
        document.getElementById('purchase-message').innerHTML = "Ошибка при выполнении покупки.";
    });
});
</script>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
