<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

if (!isset($_SESSION['user'])) {
  header('Location: /login');
  exit();
}

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user']['id'];
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  try {
    // Verify current password
    $user = $users->getUserById($userId);
    if (!password_verify($currentPassword, $user['password'])) {
      throw new Exception("Текущий пароль неверен");
    }

    // Validate new password
    if ($newPassword !== $confirmPassword) {
      throw new Exception("Новые пароли не совпадают");
    }

    if (strlen($newPassword) < 6) {
      throw new Exception("Новый пароль должен быть не менее 6 символов");
    }

    $users->updatePassword($userId, $newPassword);
    $_SESSION['success_message'] = "Пароль успешно обновлен";
  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
  }

  header('Location: /profile');
  exit();
}
