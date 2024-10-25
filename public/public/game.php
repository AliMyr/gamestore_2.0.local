<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
include '../includes/public/header.php';  // Подключаем шапку

// Получаем данные об игре из базы данных
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php if ($game): ?>
    <div class="container">
        <div class="game-details">
            <div class="game-image">
                <?php if ($game['image']): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
                <?php endif; ?>
            </div>
            <div class="game-info">
                <h1><?php echo htmlspecialchars($game['title']); ?></h1>
                <p><strong>Жанр:</strong> <?php echo htmlspecialchars($game['genre']); ?></p>
                <p><strong>Дата выхода:</strong> <?php echo htmlspecialchars($game['release_date']); ?></p>
                <p><?php echo htmlspecialchars($game['description']); ?></p>
                <p><strong>Цена:</strong> <?php echo htmlspecialchars($game['price']); ?> тенге</p>

                <form method="POST" action="add_to_cart.php">
                    <label for="quantity">Количество:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1">
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                    <button type="submit">Добавить в корзину</button>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Игра не найдена.</p>
<?php endif; ?>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
