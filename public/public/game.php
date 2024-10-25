<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Получаем ID игры из URL
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Получаем информацию об игре
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        echo "Игра не найдена!";
        exit();
    }
} else {
    echo "ID игры не указан!";
    exit();
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1><?php echo htmlspecialchars($game['title']); ?></h1>
<p><?php echo htmlspecialchars($game['description']); ?></p>
<p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>

<form method="POST" action="add_to_cart.php">
    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
    <button type="submit">Добавить в корзину</button>
</form>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
