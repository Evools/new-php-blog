<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/PostController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$posts = new PostController($conn);

if (isset($_POST['delete_post']) && !empty($_POST['delete_post'])) {
  $postId = (int)$_POST['delete_post'];
  $result = $posts->deletePost($postId);

  $_SESSION['message'] = $result ? 'Публикация успешно удалена' : 'Ошибка при удалении публикации';
  $_SESSION['message_type'] = $result ? 'success' : 'error';
}

header('Location: /admin/posts');
exit();
