<?php
session_start();
session_unset();  // Очищаем все данные сессии
session_destroy();  // Закрываем сессию

header('Location: login.php');  // Перенаправляем на страницу входа
exit();
