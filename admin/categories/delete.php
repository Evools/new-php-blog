<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/CategoriesController.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
  $db = DatabaseController::getInstance();
  $conn = $db->getConnect();
  $categories = new CategoriesController($conn);

  $category_id = (int)$_POST['delete_category'];

  try {
    if ($categories->deleteCategory($category_id)) {
      $_SESSION['success_message'] = "Категория успешно удалена";
    } else {
      $_SESSION['error_message'] = "Не удалось удалить категорию";
    }
  } catch (Exception $e) {
    $_SESSION['error_message'] = "Ошибка при удалении категории: " . $e->getMessage();
  }
}

header("Location: /admin/categories");
exit();
