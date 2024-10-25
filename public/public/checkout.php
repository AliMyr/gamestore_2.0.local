<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Проверяем, пуста ли корзина
if (count($cart) === 0) {
    $error = "Ваша корзина пуста.";
}

// Если пользователь авторизован, загружаем его данные из базы данных
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['username'];  // Подгружаем имя из базы данных
        $email = $user['email'];        // Подгружаем email из базы данных
    } else {
        $error = "Ошибка при получении данных пользователя.";
    }
} else {
    // Если пользователь не авторизован, оставляем поля пустыми
    $user_id = null;
    $username = '';  // Поле будет заполнено пользователем
    $email = '';     // Поле будет заполнено пользователем
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($error)) {
    // Если пользователь не авторизован, получаем данные из формы
    if (!$user_id) {
        $username = $_POST['username'];
        $email = $_POST['email'];
    }

    // Проверяем, что все поля заполнены
    if (empty($username) || empty($email)) {
        $error = "Все поля должны быть заполнены.";
    }

    // Если ошибок нет, продолжаем обработку заказа
    if (!isset($error)) {
        // Рассчитываем общую сумму заказа
        $total_price = 0;
        $game_ids = array_keys($cart);
        $placeholders = str_repeat('?,', count($game_ids) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM games WHERE id IN ($placeholders)");
        $stmt->execute($game_ids);
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($games as $game) {
            $total_price += $game['price'] * $cart[$game['id']]['quantity'];
        }

        // Сохраняем заказ в базе данных
        $stmt = $db->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'new')");
        $stmt->execute([$user_id, $total_price]);
        $order_id = $db->lastInsertId();

        // Сохраняем товары, связанные с заказом
        foreach ($games as $game) {
            $stmt = $db->prepare("INSERT INTO order_items (order_id, game_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $game['id'], $cart[$game['id']]['quantity'], $game['price']]);
        }

        // Очищаем корзину после оформления заказа
        unset($_SESSION['cart']);

        // Перенаправляем на страницу успешного оформления заказа
        header('Location: order_success.php');
        exit();
    }
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Оформление заказа</h1>

<?php if (isset($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label for="username">Имя:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

    <button type="submit">Оформить заказ</button>
</form>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
