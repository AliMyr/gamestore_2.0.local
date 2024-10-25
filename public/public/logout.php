<?php
session_start();
session_unset();  // Убираем все данные из сессии
session_destroy();  // Разрушаем сессию

// Перенаправляем на главную страницу
header('Location: index.php');
exit();
?>
