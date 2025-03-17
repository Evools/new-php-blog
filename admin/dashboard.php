<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/CategoriesController.php";
require_once "./Controller/UsersController.php";
require_once "./Controller/PostController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

$users = new UserController($conn);
$categories = new CategoriesController($conn);
$posts = new PostController($conn);

$totalUsers = $users->getTotalUsers();
$totalCategories = $categories->getTotalCategories();
$totalPosts = $posts->getTotalPosts();

// Добавляем список последних постов, категорий и пользователей
$recentPosts = $posts->getRecentPosts(5);
$recentCategories = $categories->getRecentCategories(3);
$recentUsers = $users->getRecentUsers(3);

$titleName = "Админ панель";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64">
    <div class="p-4 border border-gray-200 rounded-lg mt-16">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-gray-700">Всего постов</h3>
              <p class="text-3xl font-bold text-gray-900"><?= $totalPosts ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-gray-700">Пользователи</h3>
              <p class="text-3xl font-bold text-gray-900"><?= $totalUsers ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-gray-700">Категории</h3>
              <p class="text-3xl font-bold text-gray-900"><?= $totalCategories ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <div class="flex flex-col justify-between items-center mb-6 md:flex-row">
          <h3 class="text-xl font-semibold text-gray-800">Последняя активность</h3>
          <div class="flex space-x-2">
            <button onclick="showTab('posts')" id="postsTab" class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-100 text-blue-800">Посты</button>
            <button onclick="showTab('categories')" id="categoriesTab" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">Категории</button>
            <button onclick="showTab('users')" id="usersTab" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">Пользователи</button>
          </div>
        </div>

        <div id="postsContent" class="space-y-4">
          <?php if ($recentPosts): foreach ($recentPosts as $post): ?>
              <div class="flex items-center justify-between border-b pb-3">
                <div>
                  <h4 class="font-medium text-gray-800"><?= htmlspecialchars($post['title']) ?></h4>
                  <p class="text-sm text-gray-500">
                    Автор: <?= htmlspecialchars($post['author_name']) ?>
                    <?php if ($post['category_name']): ?>
                      • Категория: <?= htmlspecialchars($post['category_name']) ?>
                    <?php endif; ?>
                    • <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                  </p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full <?= $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                  <?= $post['status'] === 'published' ? 'Опубликован' : 'Черновик' ?>
                </span>
              </div>
          <?php endforeach;
          endif; ?>
        </div>

        <div id="categoriesContent" class="space-y-4 hidden">
          <?php if ($recentCategories): foreach ($recentCategories as $category): ?>
              <div class="flex items-center justify-between border-b pb-3">
                <div>
                  <h4 class="font-medium text-gray-800">Новая категория: <?= htmlspecialchars($category['name']) ?></h4>
                  <p class="text-sm text-gray-500">
                    <?= date('d.m.Y H:i', strtotime($category['created_at'])) ?>
                  </p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                  Категория
                </span>
              </div>
          <?php endforeach;
          endif; ?>
        </div>

        <div id="usersContent" class="space-y-4 hidden">
          <?php if ($recentUsers): foreach ($recentUsers as $user): ?>
              <div class="flex items-center justify-between border-b pb-3">
                <div>
                  <h4 class="font-medium text-gray-800">Новый пользователь: <?= htmlspecialchars($user['name']) ?></h4>
                  <p class="text-sm text-gray-500">
                    <?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?>
                    • <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                  </p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                  Пользователь
                </span>
              </div>
          <?php endforeach;
          endif; ?>
        </div>
      </div>

      <script>
        function showTab(tabName) {
          document.getElementById('postsContent').classList.add('hidden');
          document.getElementById('categoriesContent').classList.add('hidden');
          document.getElementById('usersContent').classList.add('hidden');

          document.getElementById('postsTab').classList.remove('bg-blue-100', 'text-blue-800');
          document.getElementById('categoriesTab').classList.remove('bg-blue-100', 'text-blue-800');
          document.getElementById('usersTab').classList.remove('bg-blue-100', 'text-blue-800');

          document.getElementById('postsTab').classList.add('text-gray-600', 'hover:bg-gray-100');
          document.getElementById('categoriesTab').classList.add('text-gray-600', 'hover:bg-gray-100');
          document.getElementById('usersTab').classList.add('text-gray-600', 'hover:bg-gray-100');

          document.getElementById(tabName + 'Content').classList.remove('hidden');
          document.getElementById(tabName + 'Tab').classList.remove('text-gray-600', 'hover:bg-gray-100');
          document.getElementById(tabName + 'Tab').classList.add('bg-blue-100', 'text-blue-800');
        }
      </script>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>