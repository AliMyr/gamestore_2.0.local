<?php
include_once "../includes/db.php"; // Подключение к базе данных
include_once "../includes/header.php";
include_once "../includes/navbar.php";

// Получаем список игр
$sql = "SELECT * FROM games";
$result = $conn->query($sql);
?>

<h2>Каталог игр</h2>

<div class="game-catalog">
    <?php while ($game = $result->fetch_assoc()): ?>
        <div class="game-item">
            <h3><?php echo htmlspecialchars($game['title']); ?></h3>
            <p><?php echo htmlspecialchars($game['description']); ?></p>
            <p><strong>Цена:</strong> <?php echo number_format($game['price'], 2); ?> ₸</p>
        </div>
    <?php endwhile; ?>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
