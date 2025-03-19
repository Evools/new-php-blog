<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/PostController.php";
require_once "./Controller/CategoryController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$posts = new PostController($conn);
$categories = new CategoryController($conn);

$all_categories = $categories->getAllCategories();

$data = $_POST;
if (isset($data['add-post'])) {
  $title = $data['title'];
  $content = $data['content'];
  $category_id = $data['category_id'];
  $status = $data['status'];

  $error = [];

  if (empty($title)) {
    $error['title'] = 'Заголовок не должен быть пустым';
  }
  if (empty($content)) {
    $error['content'] = 'Контент не должен быть пустым';
  }
  if (empty($category_id)) {
    $error['category'] = 'Выберите категорию';
  }

  if (empty($error)) {
    $user_id = $_SESSION['user_id'];
    $create_post = $posts->createPost($title, $content, $category_id, $user_id, $status);

    if ($create_post) {
      header('Location: /admin/posts');
      exit();
    } else {
      $error['general'] = 'Ошибка при создании публикации';
    }
  }
}

$titleName = "Добавить публикацию";
include "layout/head.php";
?>

<main>
  <?php include "./admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Добавить публикацию</h1>
      <a href="/admin/posts" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
        Назад
      </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <?php if (!empty($error)): ?>
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
          <ul class="list-disc list-inside">
            <?php foreach ($error as $err): ?>
              <li><?= $err ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="/admin/posts/create" class="space-y-6">
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Заголовок</label>
          <input type="text" name="title" id="title" value="<?= isset($data['title']) ? htmlspecialchars($data['title']) : '' ?>"
            class="mt-1 block w-full rounded-md border <?= isset($error['title']) ? 'border-red-300' : 'border-gray-300' ?> px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div>
          <label for="category_id" class="block text-sm font-medium text-gray-700">Категория</label>
          <div class="relative mt-1">
            <select name="category_id" id="category_id"
              class="appearance-none block w-full px-4 py-3 rounded-lg border <?= isset($error['category']) ? 'border-red-300' : 'border-gray-300' ?> bg-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              <option value="">Выберите категорию</option>
              <?php foreach ($all_categories as $category): ?>
                <option value="<?= $category['id'] ?>"
                  <?= isset($data['category_id']) && $data['category_id'] == $category['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($category['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
          <?php if (isset($error['category'])): ?>
            <p class="mt-1 text-sm text-red-600"><?= $error['category'] ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-3">Статус публикации</label>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <input type="radio" id="status_draft" name="status" value="draft"
                <?= (!isset($data['status']) || $data['status'] === 'draft') ? 'checked' : '' ?>
                class="hidden peer">
              <label for="status_draft"
                class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                  </svg>
                  <span class="text-sm font-medium">Черновик</span>
                </div>
              </label>
            </div>
            <div>
              <input type="radio" id="status_published" name="status" value="published"
                <?= (isset($data['status']) && $data['status'] === 'published') ? 'checked' : '' ?>
                class="hidden peer">
              <label for="status_published"
                class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                  </svg>
                  <span class="text-sm font-medium">Опубликовать</span>
                </div>
              </label>
            </div>
          </div>
        </div>

        <div>
          <label for="content" class="block text-sm font-medium text-gray-700">Контент</label>
          <textarea name="content" id="content" rows="10"
            class="mt-1 block w-full rounded-md border <?= isset($error['content']) ? 'border-red-300' : 'border-gray-300' ?> px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"><?= isset($data['content']) ? htmlspecialchars($data['content']) : '' ?></textarea>

          <script src="https://cdn.tiny.cloud/1/cdrkywnx15k19w5nx0mss3ocsfzfdmgxzqdtg0x9b4oxiq3b/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

          <script>
            tinymce.init({
              selector: 'textarea',
              plugins: 'link image lists table',
              toolbar: 'undo redo | blocks | bold italic | link image table | numlist bullist | removeformat',
              menubar: false,
              height: 400,
              branding: false
            });
          </script>
        </div>

        <div class="flex justify-end">
          <button type="submit" name="add-post" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Добавить публикацию
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>