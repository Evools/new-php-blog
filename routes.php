<?php

require_once __DIR__ . '/router.php';

function setSessionLifetime($days = 0, $hours = 0, $minutes = 0)
{
  $lifetime = ($days * 86400) + ($hours * 3600) + ($minutes * 60); // Перевод в секунды
  session_set_cookie_params($lifetime);
  session_start();
}

// Использование
setSessionLifetime(7, 0, 0); // Сессия сохранится на 7 дней

get('/', 'pages/index.php');

get('/sign-up', 'pages/auth/sign-up.php');
post('/sign-up', 'pages/auth/sign-up.php');

get('/sign-in', 'pages/auth/sign-in.php');
post('/login', 'pages/auth/sign-in.php');

post('/logout', function () {
  session_start(); // Запускаем сессию (если не запущена)
  unset($_SESSION['user']); // Удаляем данные пользователя
  session_destroy(); // Закрываем сессию
  header("Location: /");
});


get('/category/$slug', 'pages/category.php');


any('/404', 'pages/404.php');
