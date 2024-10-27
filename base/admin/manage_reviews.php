<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Перенаправление на страницу входа для администратора
    exit;
}
?>
<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Получение списка отзывов
$sql = "SELECT reviews.review_id, users.username, games.title, reviews.rating, reviews.review_text, reviews.review_date 
        FROM reviews 
        JOIN users ON reviews.user_id = users.user_id 
        JOIN games ON reviews.game_id = games.game_id 
        ORDER BY reviews.review_date DESC";
$result = $conn->query($sql);
?>

<h2>Управление отзывами</h2>

<div class="review-management">
    <table>
        <tr>
            <th>Пользователь</th>
            <th>Игра</th>
            <th>Рейтинг</th>
            <th>Отзыв</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php while ($review = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['username']); ?></td>
                <td><?php echo htmlspecialchars($review['title']); ?></td>
                <td><?php echo $review['rating']; ?>/5</td>
                <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                <td><?php echo $review['review_date']; ?></td>
                <td>
                    <a href="delete_review.php?id=<?php echo $review['review_id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?');">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
