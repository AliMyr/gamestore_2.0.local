<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../config/config.php';  // Подключение к базе данных

// Логика блокировки/разблокировки пользователя
if (isset($_POST['update_status'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->execute([$status, $user_id]);

    header('Location: manage_users.php');  // Перенаправляем на ту же страницу после обновления статуса
    exit();
}

// Логика удаления пользователя
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header('Location: manage_users.php');  // Перенаправляем на ту же страницу после удаления
    exit();
}

// Получаем список всех пользователей
$stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку админки
?>

<h1>Управление пользователями</h1>

<table>
    <thead>
        <tr>
            <th>Имя пользователя</th>
            <th>Email</th>
            <th>Статус</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['status']); ?></td>
                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="status">
                            <option value="active" <?php if ($user['status'] === 'active') echo 'selected'; ?>>Активен</option>
                            <option value="blocked" <?php if ($user['status'] === 'blocked') echo 'selected'; ?>>Заблокирован</option>
                        </select>
                        <button type="submit" name="update_status">Обновить статус</button>
                    </form>
                    <a href="manage_users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?');">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал админки
?>
