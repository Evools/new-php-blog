<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/NewsletterController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$newsletters = new NewsletterController($conn);

if (isset($_POST['delete_newsletter']) && !empty($_POST['delete_newsletter'])) {
  $newsletterId = (int)$_POST['delete_newsletter'];
  $result = $newsletters->deleteNewsletter($newsletterId);

  $_SESSION['message'] = $result ? 'Рассылка успешно удалена' : 'Ошибка при удалении рассылки';
  $_SESSION['message_type'] = $result ? 'success' : 'error';
}

header('Location: /admin/newsletters');
exit();
