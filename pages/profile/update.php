<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

// Check if user is logged in
if (!isset($_SESSION['user'])) {
  header('Location: /login');
  exit();
}

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user']['id'];
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');

  try {
    $users->updateUser($userId, $name, $email);
    // Update session data
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['success_message'] = "Профиль успешно обновлен";
  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
  }

  header('Location: /profile');
  exit();
}
