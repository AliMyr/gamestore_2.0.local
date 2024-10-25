<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
include '../includes/admin/header.php';  // Подключаем шапку

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];

    // Обработка загрузки изображения
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Записываем игру в базу данных
        $stmt = $db->prepare("INSERT INTO games (title, description, price, image, genre, release_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $image, $genre, $release_date]);
        echo "Игра добавлена!";
    } else {
        echo "Ошибка при загрузке изображения.";
    }
}
?>

<h2>Добавить игру</h2>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название:</label>
    <input type="text" id="title" name="title" required><br>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="price">Цена (в тенге):</label>
    <input type="number" id="price" name="price" required><br>

    <label for="genre">Жанр:</label>
    <input type="text" id="genre" name="genre" required><br>

    <label for="release_date">Дата выхода:</label>
    <input type="date" id="release_date" name="release_date" required><br>

    <label for="image">Изображение:</label>
    <input type="file" id="image" name="image" required><br><br>

    <button type="submit">Добавить игру</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал
?>
