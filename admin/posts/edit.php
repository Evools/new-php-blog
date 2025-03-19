<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/PostController.php";
require_once "./Controller/CategoryController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$posts = new PostController($conn);
$categoryController = new CategoryController($conn);

$categories = $categoryController->getAllCategories();

$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$postId = (int)end($urlParts);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $postId = (int)$_POST['post_id'];
  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  $status = $_POST['status'];
  $category = (int)$_POST['category'];

  try {
    if (empty($title) || empty($content)) {
      throw new Exception("Заголовок и содержание обязательны для заполнения");
    }

    $posts->updatePost($postId, $title, $content, $status, $category);
    $_SESSION['success_message'] = "Пост успешно обновлен";
  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
  }
}

// Get post data after possible update
try {
  $post = $posts->getPostById($postId);
  if (!$post) {
    $_SESSION['error_message'] = "Пост не найден";
    header('Location: /admin/posts');
    exit();
  }
} catch (Exception $e) {
  $_SESSION['error_message'] = $e->getMessage();
  header('Location: /admin/posts');
  exit();
}

$titleName = "Редактирование поста";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Редактирование поста</h1>
      <p class="mt-1 text-sm text-gray-600">Измените данные поста "<?= htmlspecialchars($post['title']) ?>"</p>
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
      <form method="POST" action="/admin/posts/edit/<?= $postId ?>" class="space-y-6" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

        <div class="grid grid-cols-1 gap-6">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Заголовок</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($post['title']) ?>"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          </div>

          <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Содержание</label>
            <textarea name="content" id="content" rows="10"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"><?= htmlspecialchars($post['content']) ?></textarea>

            <!-- TinyMCE Script -->
            <script src="https://cdn.tiny.cloud/1/cdrkywnx15k19w5nx0mss3ocsfzfdmgxzqdtg0x9b4oxiq3b/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

            <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
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

          <div>
            <label class="block text-sm font-medium text-gray-700">Статус публикации</label>
            <div class="mt-1 grid grid-cols-2 gap-3">
              <div>
                <input type="radio" id="status_draft" name="status" value="draft" <?= $post['status'] === 'draft' ? 'checked' : '' ?> class="hidden peer">
                <label for="status_draft" class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                  <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                      <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                      <polyline points="14 2 14 8 20 8" />
                      <line x1="16" y1="13" x2="8" y2="13" />
                      <line x1="16" y1="17" x2="8" y2="17" />
                      <line x1="10" y1="9" x2="8" y2="9" />
                    </svg>
                    <span class="text-sm font-medium">Черновик</span>
                  </div>
                </label>
              </div>
              <div>
                <input type="radio" id="status_published" name="status" value="published" <?= $post['status'] === 'published' ? 'checked' : '' ?> class="hidden peer">
                <label for="status_published" class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                  <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe">
                      <circle cx="12" cy="12" r="10" />
                      <line x1="2" y1="12" x2="22" y2="12" />
                      <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                    <span class="text-sm font-medium">Опубликован</span>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-3">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Сохранить изменения
          </button>
          <a href="/admin/posts" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
            Отмена
          </a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>