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

// Получаем список пользователей
$sql = "SELECT user_id, username, email, registration_date FROM users";
$result = $conn->query($sql);
?>

<h2>Управление пользователями</h2>

<div class="user-management">
    <table>
        <tr>
            <th>Имя пользователя</th>
            <th>Email</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['registration_date']); ?></td>
                <td>
                    <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?');">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
