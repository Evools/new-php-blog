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

// Add these routes
post('/profile/update', '/pages/profile/update.php');
post('/profile/update-password', '/pages/profile/update-password.php');
get('/profile', '/pages/profile/view.php');

post('/logout', function () {
  session_start(); // Запускаем сессию (если не запущена)
  unset($_SESSION['user']); // Удаляем данные пользователя
  session_destroy(); // Закрываем сессию
  header("Location: /");
});

get('/category/$slug', 'pages/category.php');



// Доступ к админ панели только у тех у кого роль admin
function adminAuth($path)
{
  return function () use ($path) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
      header("Location: /");
      exit();
    }
    require __DIR__ . '/' . $path;
  };
}

// Dashboard
get('/admin', adminAuth('admin/dashboard.php'));
get('/admin/categories', adminAuth('admin/categories/view.php'));
get('/admin/categories/create', adminAuth('admin/categories/create.php'));
post('/admin/categories/create', adminAuth('admin/categories/create.php'));
get('/admin/categories/edit/$id', adminAuth('admin/categories/edit.php'));
post('/admin/categories/edit/$id', adminAuth('admin/categories/edit.php'));
post('/admin/categories/delete', adminAuth('admin/categories/delete.php'));


// Posts
get('/admin/posts', adminAuth('admin/posts/view.php'));
get('/admin/posts/create', adminAuth('admin/posts/create.php'));
post('/admin/posts/create', adminAuth('admin/posts/create.php'));
get('/admin/posts/edit/$id', adminAuth('admin/posts/edit.php'));
post('/admin/posts/edit/$id', adminAuth('admin/posts/edit.php'));
post('/admin/posts/delete', adminAuth('admin/posts/delete.php'));

// Users
get('/admin/users', adminAuth('admin/users/view.php'));
get('/admin/users/create', adminAuth('admin/users/create.php'));
post('/admin/users/create', adminAuth('admin/users/create.php'));
get('/admin/users/edit/$id', '/admin/users/edit.php');
post('/admin/users/edit/$id', '/admin/users/edit.php');
post('/admin/users/delete', adminAuth('admin/users/delete.php'));

// Newsletters
get('/admin/newsletters', adminAuth('admin/newsletters/view.php'));
get('/admin/newsletters/create', adminAuth('admin/newsletters/create.php'));
post('/admin/newsletters/create', adminAuth('admin/newsletters/create.php'));
get('/admin/newsletters/edit/$id', adminAuth('admin/newsletters/edit.php'));
post('/admin/newsletters/edit/$id', adminAuth('admin/newsletters/edit.php'));
post('/admin/newsletters/delete', adminAuth('admin/newsletters/delete.php'));
post('/admin/newsletters/send/$id', adminAuth('admin/newsletters/send.php'));

// Subscribers
get('/admin/subscribers', adminAuth('admin/subscribers/view.php'));
post('/admin/subscribers/delete', adminAuth('admin/subscribers/delete.php'));
post('/admin/subscribers/status', adminAuth('admin/subscribers/status.php'));

any('/404', 'pages/404.php');
