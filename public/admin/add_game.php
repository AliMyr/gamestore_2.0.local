<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Обрабатываем изображение
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        // Определяем директорию для загрузки
        $uploadDir = '../uploads/games/';
        $destination = $uploadDir . $imageName;

        // Перемещаем загруженный файл
        if (move_uploaded_file($imageTmpPath, $destination)) {
            $image = $imageName;
        }
    }

    // Вставляем данные игры в базу данных
    $stmt = $db->prepare("INSERT INTO games (title, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $price, $image]);

    echo "Игра успешно добавлена!";
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить игру</title>
</head>
<body>

<h1>Добавить новую игру</h1>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название игры:</label>
    <input type="text" name="title" id="title" required><br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description" required></textarea><br>

    <label for="price">Цена:</label>
    <input type="number" name="price" id="price" required><br>

    <label for="image">Изображение:</label>
    <input type="file" name="image" id="image"><br>

    <button type="submit">Добавить</button>
</form>


</body>
</html>
