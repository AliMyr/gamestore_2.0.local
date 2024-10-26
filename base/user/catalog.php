<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";

// Получение списка уникальных жанров из базы данных
$genres_sql = "SELECT DISTINCT genre FROM games WHERE genre IS NOT NULL";
$genres_result = $conn->query($genres_sql);
$genres = [];
while ($row = $genres_result->fetch_assoc()) {
    $genres[] = $row['genre'];
}

// Определение параметров фильтрации и сортировки из URL
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'title';

// Создание основного SQL-запроса с фильтрацией и сортировкой
$sql = "SELECT * FROM games WHERE 1=1";

if ($genre) {
    $sql .= " AND genre = ?";
}

if ($sort == 'price_asc') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == 'price_desc') {
    $sql .= " ORDER BY price DESC";
} elseif ($sort == 'release_date') {
    $sql .= " ORDER BY release_date DESC";
} else {
    $sql .= " ORDER BY title ASC";
}

$stmt = $conn->prepare($sql);

// Применение фильтрации по жанру, если выбран жанр
if ($genre) {
    $stmt->bind_param("s", $genre);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Каталог игр</h2>

<!-- Форма для фильтрации и сортировки -->
<form method="GET" action="catalog.php">
    <label for="genre">Жанр:</label>
    <select name="genre" id="genre">
        <option value="">Все жанры</option>
        <?php foreach ($genres as $g): ?>
            <option value="<?php echo htmlspecialchars($g); ?>" <?php if ($genre == $g) echo 'selected'; ?>><?php echo htmlspecialchars($g); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="sort">Сортировать по:</label>
    <select name="sort" id="sort">
        <option value="title" <?php if ($sort == 'title') echo 'selected'; ?>>Название</option>
        <option value="price_asc" <?php if ($sort == 'price_asc') echo 'selected'; ?>>Цена: по возрастанию</option>
        <option value="price_desc" <?php if ($sort == 'price_desc') echo 'selected'; ?>>Цена: по убыванию</option>
        <option value="release_date" <?php if ($sort == 'release_date') echo 'selected'; ?>>Дата выпуска</option>
    </select>

    <input type="submit" value="Применить">
</form>

<div class="game-catalog">
    <?php while ($game = $result->fetch_assoc()): ?>
        <div class="game-item">
            <a href="game_details.php?id=<?php echo $game['game_id']; ?>"><h3><?php echo htmlspecialchars($game['title']); ?></h3></a>
            <p><?php echo htmlspecialchars($game['description']); ?></p>
            <p><strong>Цена:</strong> <?php echo number_format($game['price'], 2); ?> ₸</p>
            <p><strong>Жанр:</strong> <?php echo htmlspecialchars($game['genre']); ?></p>

            <?php
            // Получение среднего рейтинга для игры
            $rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE game_id = ?";
            $rating_stmt = $conn->prepare($rating_sql);
            $rating_stmt->bind_param("i", $game['game_id']);
            $rating_stmt->execute();
            $rating_result = $rating_stmt->get_result();
            $avg_rating = $rating_result->fetch_assoc()['avg_rating'];
            ?>
            <p><strong>Средний рейтинг:</strong> <?php echo $avg_rating ? round($avg_rating, 1) : "Нет рейтинга"; ?> / 5</p>
        </div>
    <?php endwhile; ?>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
