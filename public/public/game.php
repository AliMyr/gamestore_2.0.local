<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, был ли передан ID игры через GET-параметры
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Подготавливаем запрос для получения информации о конкретной игре
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверяем, что игра найдена
    if (!$game) {
        echo "Товар не найден.";
        exit();
    }
} else {
    echo "ID игры не указан.";
    exit();
}
?>

<h1><?php echo htmlspecialchars($game['title']); ?></h1>
<p><?php echo htmlspecialchars($game['description']); ?></p>
<p>Цена: <?php echo htmlspecialchars($game['price']); ?> руб.</p>
<img src="images/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">

<form method="POST" action="add_to_cart.php">
    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
    <button type="submit">Добавить в корзину</button>
</form>
