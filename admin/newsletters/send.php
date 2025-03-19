<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/NewsletterController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

$newsletter = new NewsletterController($conn);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$newsletterData = $newsletter->getNewsletterById($id);

if (!$newsletterData || $newsletterData['status'] === 'sent') {
  header("Location: /admin/newsletters");
  exit;
}

try {
  if ($newsletter->sendNewsletter($id)) {
    $_SESSION['success'] = "Рассылка успешно отправлена";
  } else {
    $_SESSION['error'] = "Ошибка при отправке рассылки";
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
}

header("Location: /admin/newsletters");
exit;
