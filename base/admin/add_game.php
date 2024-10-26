<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $release_date = $_POST['release_date'];
    $genre = $_POST['genre'];

    $sql = "INSERT INTO games (title, description, price, release_date, genre) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $title, $description, $price, $release_date, $genre);

    if ($stmt->execute()) {
        echo "<p>Игра успешно добавлена!</p>";
    } else {
        echo "<p>Ошибка при добавлении игры.</p>";
    }
}
?>

<h2>Добавить новую игру</h2>

<form method="POST">
    <label>Название:</label>
    <input type="text" name="title" required><br>
    <label>Описание:</label>
    <textarea name="description" required></textarea><br>
    <label>Цена:</label>
    <input type="number" step="0.01" name="price" required><br>
    <label>Дата выпуска:</label>
    <input type="date" name="release_date" required><br>
    <label>Жанр:</label>
    <input type="text" name="genre" required><br>
    <input type="submit" value="Добавить игру">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
