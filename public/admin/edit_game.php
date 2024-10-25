<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
include '../includes/admin/header.php';  // Подключаем шапку

// Проверяем, передан ли ID игры для редактирования
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];

    // Проверка, загружено ли новое изображение
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        // Если изображение не загружено, оставляем старое
        $image = $game['image'];
    }

    // Обновляем данные игры в базе данных
    $stmt = $db->prepare("UPDATE games SET title = ?, description = ?, price = ?, image = ?, genre = ?, release_date = ? WHERE id = ?");
    $stmt->execute([$title, $description, $price, $image, $genre, $release_date, $game_id]);

    echo "Игра обновлена!";
    // Обновляем данные игры после изменения
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h2>Редактировать игру</h2>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($game['title']); ?>" required><br>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($game['description']); ?></textarea><br>

    <label for="price">Цена (в тенге):</label>
    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($game['price']); ?>" required><br>

    <label for="genre">Жанр:</label>
    <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($game['genre']); ?>" required><br>

    <label for="release_date">Дата выхода:</label>
    <input type="date" id="release_date" name="release_date" value="<?php echo htmlspecialchars($game['release_date']); ?>" required><br>

    <label for="image">Изображение (оставьте пустым, чтобы не менять):</label>
    <input type="file" id="image" name="image"><br><br>

    <button type="submit">Сохранить изменения</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал
?>
