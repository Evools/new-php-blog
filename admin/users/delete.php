<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);

if (isset($_POST['delete_user']) && !empty($_POST['delete_user'])) {
  $userId = (int)$_POST['delete_user'];
  $result = $users->deleteUser($userId);

  $_SESSION['message'] = $result ? 'Пользователь успешно удален' : 'Ошибка при удалении пользователя';
  $_SESSION['message_type'] = $result ? 'success' : 'error';
}

header('Location: /admin/users');
exit();
