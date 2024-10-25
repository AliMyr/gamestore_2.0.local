<?php
include '../config/config.php';  // Подключение к базе данных
include '../includes/public/header.php';  // Подключаем шапку

$game_id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch();

if (!$game) {
    echo "<p>Игра не найдена.</p>";
    include '../includes/public/footer.php';
    exit();
}
?>

<h1><?php echo htmlspecialchars($game['title']); ?></h1>

<?php if ($game['image']): ?>
    <img src="../uploads/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" width="200">
<?php endif; ?>

<p><?php echo htmlspecialchars($game['description']); ?></p>
<p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>

<form method="POST" action="add_to_cart.php">
    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
    <label for="quantity">Количество:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <button type="submit">Добавить в корзину</button>
</form>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
