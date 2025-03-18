<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/CategoriesController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$categories = new CategoriesController($conn);

// Add message handling
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

$all_categories = $categories->getCategories();

$titleName = "Админ панель";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <!-- Add message display -->
    <?php if (isset($success_message)): ?>
      <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        <?= $success_message ?>
      </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
      <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <?= $error_message ?>
      </div>
    <?php endif; ?>
    
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Категории</h1>
      <a href="/admin/categories/create/" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        Добавить категорию
      </a>
    </div>

    <div class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3">ID</th>
              <th scope="col" class="px-6 py-3">Название</th>
              <th scope="col" class="px-6 py-3">Slug</th>
              <th scope="col" class="px-6 py-3">Дата создания</th>
              <th scope="col" class="px-6 py-3">Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_categories as $category): ?>
              <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4"><?= $category['id']; ?></td>
                <td class="px-6 py-4 font-medium text-gray-900"><?= $category['name']; ?></td>
                <td class="px-6 py-4"><?= $category['slug']; ?></td>
                <td class="px-6 py-4"><?= $category['created_at']; ?></td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <a href="/admin/categories/edit/<?= $category['id']; ?>" class="text-blue-600 hover:text-blue-900">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>
                    <button onclick="openDeleteModal(<?= $category['id']; ?>, '<?= htmlspecialchars($category['name']); ?>')" class="text-red-600 hover:text-red-900">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="mt-3 text-center">
      <svg class="mx-auto mb-4 w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <h3 class="text-lg font-medium text-gray-900">Удалить категорию?</h3>
      <div class="mt-2 px-7 py-3">
        <p class="text-gray-500">Вы действительно хотите удалить категорию <span id="deleteCategoryName"></span>?</p>
      </div>
      <div class="flex justify-center gap-3 mt-3">
        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
          Отмена
        </button>
        <form method="POST" action="/admin/categories/delete">
          <input type="hidden" name="delete_category" id="deleteCategoryId">
          <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Удалить
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function openDeleteModal(categoryId, categoryName) {
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteCategoryId').value = categoryId;
    document.getElementById('deleteModal').classList.remove('hidden');
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
  }
</script>

<?php include "layout/footer.php"; ?>