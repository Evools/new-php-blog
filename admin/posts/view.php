<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/PostController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$posts = new PostController($conn);

$all_posts = $posts->getAllPosts();

$titleName = "Админ панель";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Публикации</h1>
      <a href="/admin/posts/create/" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        Добавить публикацию
      </a>
    </div>

    <div class="mb-4 flex gap-4">
      <div class="flex-1">
        <input type="text" id="searchInput" placeholder="Поиск публикаций..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
      </div>
      <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Все категории</option>
        <option value="news">Новости</option>
        <option value="articles">Статьи</option>
      </select>
    </div>

    <div class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3">ID</th>
              <th scope="col" class="px-6 py-3">Заголовок</th>
              <th scope="col" class="px-6 py-3">Категория</th>
              <th scope="col" class="px-6 py-3">Автор</th>
              <th scope="col" class="px-6 py-3">Статус</th>
              <th scope="col" class="px-6 py-3">Дата публикации</th>
              <th scope="col" class="px-6 py-3">Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_posts as $post): ?>
              <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4"><?= $post['id'] ?></td>
                <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($post['title'] ?? '') ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($post['category_name'] ?? '') ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($post['author_name'] ?? '') ?></td>
                <td class="px-6 py-4">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    <?= ($post['status'] ?? '') === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= ($post['status'] ?? '') === 'published' ? 'Опубликован' : 'Черновик' ?>
                  </span>
                </td>
                <td class="px-6 py-4"><?= isset($post['created_at']) ? date('d.m.Y', strtotime($post['created_at'])) : '' ?></td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <a href="/admin/posts/edit/<?= $post['id'] ?>" class="text-blue-600 hover:text-blue-900">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>
                    <button onclick="openDeleteModal(<?= $post['id'] ?>, '<?= htmlspecialchars($post['title']) ?>')" class="text-red-600 hover:text-red-900">
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

      <!-- Pagination -->
      <div class="flex items-center justify-between p-4 border-t">
        <div class="text-sm text-gray-700">
          Показано <span>1</span>-<span>10</span> из <span>20</span>
        </div>
        <div class="flex gap-2">
          <button class="px-3 py-1 text-sm border rounded hover:bg-gray-50">Назад</button>
          <div class="flex gap-2">
            <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded">1</button>
            <button class="px-3 py-1 text-sm border rounded hover:bg-gray-50">2</button>
          </div>
          <button class="px-3 py-1 text-sm border rounded hover:bg-gray-50">Вперед</button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Add Delete Modal -->
<div id="deletePostModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="mt-3 text-center">
      <h3 class="text-lg font-medium text-gray-900">Удалить публикацию?</h3>
      <div class="mt-2 px-7 py-3">
        <p class="text-gray-500">Вы действительно хотите удалить публикацию <span id="deletePostTitle"></span>?</p>
      </div>
      <div class="flex justify-center gap-3 mt-3">
        <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
          Отмена
        </button>
        <form method="POST" action="/admin/posts/delete">
          <input type="hidden" name="delete_post" id="deletePostId">
          <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Удалить
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function openDeleteModal(postId, postTitle) {
    document.getElementById('deletePostTitle').textContent = postTitle;
    document.getElementById('deletePostId').value = postId;
    document.getElementById('deletePostModal').classList.remove('hidden');
  }

  function closeDeleteModal() {
    document.getElementById('deletePostModal').classList.add('hidden');
  }
</script>

<?php include "layout/footer.php"; ?>