<?php
session_start();
session_unset();
session_destroy();
header("Location: admin_login.php"); // Перенаправление на страницу входа после выхода
exit;
?>
