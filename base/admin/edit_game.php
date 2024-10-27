<?php
include_once "../includes/db.php"; // Подключение к базе данных
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Проверка ID игры
if (!isset($_GET['id'])) {
    die("ID игры не указан.");
}

$game_id = $_GET['id'];

// Получение данных игры для редактирования
$sql = "SELECT * FROM games WHERE game_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

// Проверка, что игра найдена
if (!$game) {
    die("Игра не найдена.");
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $developer = $_POST['developer'];
    $publisher = $_POST['publisher'];
    $price = $_POST['price'];
    $release_date = $_POST['release_date'];
    $age_rating = $_POST['age_rating'];
    $genre = $_POST['genre'];
    $system_requirements = $_POST['system_requirements'];
    $trailer_url = $_POST['trailer_url'];
    $cover_image = $_POST['cover_image'];

    // Обновленный SQL-запрос и bind_param с правильными параметрами
    $update_sql = "UPDATE games SET title = ?, description = ?, developer = ?, publisher = ?, price = ?, release_date = ?, age_rating = ?, genre = ?, system_requirements = ?, trailer_url = ?, cover_image = ? WHERE game_id = ?";
    $update_stmt = $conn->prepare($update_sql);

    // Связываем параметры с запросом
    $update_stmt->bind_param("ssssdssssssi", $title, $description, $developer, $publisher, $price, $release_date, $age_rating, $genre, $system_requirements, $trailer_url, $cover_image, $game_id);

    // Выполнение запроса
    if ($update_stmt->execute()) {
        echo "<p>Игра успешно обновлена!</p>";
    } else {
        echo "<p>Ошибка при обновлении игры.</p>";
    }
}
?>

<h2>Редактировать игру</h2>

<form method="POST">
    <label>Название:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($game['title']); ?>" required><br>

    <label>Описание:</label>
    <textarea name="description" required><?php echo htmlspecialchars($game['description']); ?></textarea><br>

    <label>Разработчик:</label>
    <input type="text" name="developer" value="<?php echo htmlspecialchars($game['developer']); ?>"><br>

    <label>Издатель:</label>
    <input type="text" name="publisher" value="<?php echo htmlspecialchars($game['publisher']); ?>"><br>

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" value="<?php echo $game['price']; ?>" required><br>

    <label>Дата выпуска:</label>
    <input type="date" name="release_date" value="<?php echo $game['release_date']; ?>" required><br>

    <label>Возрастной рейтинг:</label>
    <input type="text" name="age_rating" value="<?php echo htmlspecialchars($game['age_rating']); ?>"><br>

    <label>Жанр:</label>
    <input type="text" name="genre" value="<?php echo htmlspecialchars($game['genre']); ?>" required><br>

    <label>Системные требования:</label>
    <textarea name="system_requirements"><?php echo htmlspecialchars($game['system_requirements']); ?></textarea><br>

    <label>Ссылка на трейлер:</label>
    <input type="text" name="trailer_url" value="<?php echo htmlspecialchars($game['trailer_url']); ?>"><br>

    <label>Обложка (URL):</label>
    <input type="text" name="cover_image" value="<?php echo htmlspecialchars($game['cover_image']); ?>"><br>

    <input type="submit" value="Сохранить изменения">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
