<?php

require_once __DIR__ . '/router.php';

session_start();

get('/', 'pages/index.php');

get('/sign-up', 'pages/auth/sign-up.php');
post('/sign-up', 'pages/auth/sign-up.php');

get('/category/$slug', 'pages/category.php');

get('/sign-in', 'pages/auth/sign-in.php');
post('/login', 'pages/auth/sign-in.php');

require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

post('/logout', function () {
  session_start(); // Запускаем сессию (если не запущена)
  unset($_SESSION['user']); // Удаляем данные пользователя
  session_destroy(); // Закрываем сессию
  header("Location: /");
});


any('/404', 'pages/404.php');
