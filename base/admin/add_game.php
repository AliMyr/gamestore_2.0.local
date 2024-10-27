<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Обработка формы
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

    $sql = "INSERT INTO games (title, description, developer, publisher, price, release_date, age_rating, genre, system_requirements, trailer_url, cover_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdssssss", $title, $description, $developer, $publisher, $price, $release_date, $age_rating, $genre, $system_requirements, $trailer_url, $cover_image);

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

    <label>Разработчик:</label>
    <input type="text" name="developer"><br>

    <label>Издатель:</label>
    <input type="text" name="publisher"><br>

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" required><br>

    <label>Дата выпуска:</label>
    <input type="date" name="release_date" required><br>

    <label>Возрастной рейтинг:</label>
    <input type="text" name="age_rating"><br>

    <label>Жанр:</label>
    <input type="text" name="genre" required><br>

    <label>Системные требования:</label>
    <textarea name="system_requirements"></textarea><br>

    <label>Ссылка на трейлер:</label>
    <input type="text" name="trailer_url"><br>

    <label>Обложка (URL):</label>
    <input type="text" name="cover_image"><br>

    <input type="submit" value="Добавить игру">
</form>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
