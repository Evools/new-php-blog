<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/CategoriesController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$categories = new CategoriesController($conn);

$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$categoryId = (int)end($urlParts);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $categoryId = (int)$_POST['category_id'];
  $name = trim($_POST['name']);

  try {
    if (empty($name)) {
      throw new Exception("Название категории обязательно для заполнения");
    }

    $categories->updateCategory($categoryId, $name);
    $_SESSION['success_message'] = "Категория успешно обновлена";
    header('Location: /admin/categories/edit/' . $categoryId);
    exit();
  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
  }
}

try {
  $category = $categories->getCategoryById($categoryId);
  if (!$category) {
    $_SESSION['error_message'] = "Категория не найдена";
    header('Location: /admin/categories');
    exit();
  }
} catch (Exception $e) {
  $_SESSION['error_message'] = $e->getMessage();
  header('Location: /admin/categories');
  exit();
}

$titleName = "Редактирование категории";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Редактирование категории</h1>
      <p class="mt-1 text-sm text-gray-600">Измените данные категории "<?= htmlspecialchars($category['name']) ?>"</p>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
      <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        <?= $_SESSION['success_message'] ?>
        <?php unset($_SESSION['success_message']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
      <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <?= $_SESSION['error_message'] ?>
        <?php unset($_SESSION['error_message']); ?>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow p-6">
      <form method="POST" action="/admin/categories/edit/<?= $categoryId ?>" class="space-y-6">
        <input type="hidden" name="category_id" value="<?= $category['id'] ?>">

        <div class="grid grid-cols-1 gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Название категории</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          </div>
        </div>

        <div class="flex gap-3">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Сохранить изменения
          </button>
          <a href="/admin/categories" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
            Отмена
          </a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>