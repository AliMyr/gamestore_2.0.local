<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
include '../includes/public/header.php';  // Подключаем шапку

// Получаем все жанры для фильтрации
$stmt = $db->prepare("SELECT DISTINCT genre FROM games");
$stmt->execute();
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Фильтрация игр по жанру
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : null;

// Получаем все игры, с учетом фильтрации
if ($genre_filter) {
    $stmt = $db->prepare("SELECT * FROM games WHERE genre = ?");
    $stmt->execute([$genre_filter]);
} else {
    $stmt = $db->prepare("SELECT * FROM games");
    $stmt->execute();
}

$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Каталог игр</h1>

<form method="GET" action="games.php">
    <label for="genre">Выберите жанр:</label>
    <select name="genre" id="genre">
        <option value="">Все жанры</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?php echo htmlspecialchars($genre['genre']); ?>" <?php if ($genre_filter === $genre['genre']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($genre['genre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Фильтр</button>
</form>

<ul>
    <?php foreach ($games as $game): ?>
        <li>
            <h2><a href="game.php?id=<?php echo $game['id']; ?>"><?php echo htmlspecialchars($game['title']); ?></a></h2>
            <img src="<?php echo htmlspecialchars($game['image']); ?>" alt="Изображение игры" style="max-width: 200px;">
            <p><?php echo htmlspecialchars($game['description']); ?></p>
            <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
            <p>Жанр: <?php echo htmlspecialchars($game['genre']); ?></p>
        </li>
    <?php endforeach; ?>
</ul>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
