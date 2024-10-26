<nav>
    <a href="/admin/index.php">Главная админки</a> |
    <a href="/admin/pages/add_game.php">Добавить игру</a> |
    <a href="/admin/pages/manage_games.php">Управление играми</a> |
    <a href="/admin/pages/manage_orders.php">Управление заказами</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        | <a href="/public/pages/logout.php">Выйти</a>
    <?php endif; ?>
</nav>