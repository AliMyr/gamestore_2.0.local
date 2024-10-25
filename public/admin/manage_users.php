<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем всех пользователей
$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Управление пользователями</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Имя пользователя</th>
        <th>Email</th>
        <th>Дата регистрации</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo $user['created_at']; ?></td>
            <td>
                <a href="view_user.php?id=<?php echo $user['id']; ?>">Просмотреть заказы</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
