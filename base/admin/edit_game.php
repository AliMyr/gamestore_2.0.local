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
    $price = $_POST['price'];
    $release_date = $_POST['release_date'];
    $genre = $_POST['genre'];

    $update_sql = "UPDATE games SET title = ?, description = ?, price = ?, release_date = ?, genre = ? WHERE game_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssdssi", $title, $description, $price, $release_date, $genre, $game_id);

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
    <label>Цена:</label>
    <input type="number" step="0.01" name="price" value="<?php echo $game['price']; ?>" required><br>
    <label>Дата выпуска:</label>
    <input type="date" name="release_date" value="<?php echo $game['release_date']; ?>" required><br>
    <label>Жанр:</label>
    <input type="text" name="genre" value="<?php echo htmlspecialchars($game['genre']); ?>" required><br>
    <input type="submit" value="Сохранить изменения">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
