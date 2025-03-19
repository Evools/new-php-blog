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

$recentPosts = $posts->getRecentPosts(6);
$recentCategories = $categories->getRecentCategories(6);
$recentUsers = $users->getRecentUsers(6);

$titleName = "Админ панель";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64">
    <div class="p-4 border border-gray-200 rounded-lg mt-16 bg-gray-50">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors duration-300">
              <svg class="w-8 h-8 transform hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-gray-700">Всего постов</h3>
              <div class="flex items-baseline">
                <p class="text-3xl font-bold text-gray-900"><?= $totalPosts ?></p>
                <p class="ml-2 text-sm text-gray-500">публикаций</p>
              </div>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Последнее обновление: <?= date('d.m.Y H:i') ?>
            </p>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-gray-700">Пользователи</h3>
              <div class="flex items-baseline">
                <p class="text-3xl font-bold text-gray-900"><?= $totalUsers ?></p>
                <p class="ml-2 text-sm text-gray-500">активных</p>
              </div>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Последнее обновление: <?= date('d.m.Y H:i') ?>
            </p>

          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-gray-700">Категории</h3>
              <div class="flex items-baseline">
                <p class="text-3xl font-bold text-gray-900"><?= $totalCategories ?></p>
                <p class="ml-2 text-sm text-gray-500">разделов</p>
              </div>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100">

            <p class="text-sm text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Последнее обновление: <?= date('d.m.Y H:i') ?>
            </p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <div class="flex flex-col gap-5">
          <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Последняя активность
          </h3>
          <div class="flex max-w-max space-x-2 bg-gray-100 p-1 rounded-lg">
            <button onclick="showTab('posts')" id="postsTab" class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 bg-blue-100 text-blue-800">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Посты
              </span>
            </button>
            <button onclick="showTab('categories')" id="categoriesTab" class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 text-gray-600 hover:bg-gray-200">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Категории
              </span>
            </button>
            <button onclick="showTab('users')" id="usersTab" class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 text-gray-600 hover:bg-gray-200">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Пользователи
              </span>
            </button>
          </div>

          <div id="postsContent" class="space-y-4">
            <?php if ($recentPosts): foreach ($recentPosts as $post): ?>
                <div class="flex items-center justify-between border-b pb-3 hover:bg-gray-50 p-4 rounded-xl transition-all duration-300 group">
                  <div>
                    <h4 class="font-medium text-gray-800 hover:text-blue-600 transition-colors">
                      <a href="/admin/posts/edit/<?= $post['id'] ?>" class="flex items-center gap-2">
                        <?= htmlspecialchars($post['title']) ?>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                      </a>
                    </h4>
                    <p class="text-sm text-gray-500 flex items-center gap-2 mt-1">
                      <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <?= htmlspecialchars($post['author_name'] ?? 'Неизвестный') ?>
                      </span>
                      <?php if (!empty($post['category_name'])): ?>
                        <span class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                          </svg>
                          <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                      <?php endif; ?>
                      <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                      </span>
                    </p>
                  </div>
                  <span class="px-3 py-1 text-sm rounded-full <?= $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?> flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <?php if ($post['status'] === 'published'): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                      <?php endif; ?>
                    </svg>
                    <?= $post['status'] === 'published' ? 'Опубликован' : 'Черновик' ?>
                  </span>
                </div>
            <?php endforeach;
            endif; ?>
          </div>

          <div id="categoriesContent" class="space-y-4 hidden">
            <?php if ($recentCategories): foreach ($recentCategories as $category): ?>
                <div class="flex items-center justify-between border-b pb-3 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                  <div>
                    <h4 class="font-medium text-gray-800 hover:text-purple-600 transition-colors">
                      <p class="flex items-center gap-2">
                        <?= htmlspecialchars($category['name']) ?>
                      </p>
                    </h4>
                    <p class="text-sm text-gray-500 flex items-center gap-2 mt-1">
                      <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?= date('d.m.Y H:i', strtotime($category['created_at'])) ?>
                      </span>
                    </p>
                  </div>
                  <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Категория
                  </span>
                </div>
            <?php endforeach;
            endif; ?>
          </div>

          <div id="usersContent" class="space-y-4 hidden">
            <?php if ($recentUsers): foreach ($recentUsers as $user): ?>
                <div class="flex items-center justify-between border-b pb-3 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                  <div>
                    <h4 class="font-medium text-gray-800 hover:text-green-600 transition-colors">
                      <p class="flex items-center gap-2">
                        <?= htmlspecialchars($user['name']) ?>
                      </p>
                    </h4>
                    <p class="text-sm text-gray-500 flex items-center gap-2 mt-1">
                      <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?>
                      </span>
                      <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                      </span>
                    </p>
                  </div>
                  <span class="px-3 py-1 text-sm rounded-full <?= $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?> flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <?php if ($user['role'] === 'admin'): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      <?php endif; ?>
                    </svg>
                    <?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?>
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

<style>
  .group:hover svg {
    transform: scale(1.1);
    transition: transform 0.3s ease;
  }

  .group:hover h4 {
    color: #3B82F6;
    transition: color 0.3s ease;
  }
</style>

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

  // Add smooth transition
  const content = document.getElementById(tabName + 'Content');
  content.style.opacity = '0';
  content.classList.remove('hidden');
  setTimeout(() => {
    content.style.opacity = '1';
    content.style.transition = 'opacity 0.3s ease-in-out';
  }, 50);
</script>
<?php include "layout/footer.php"; ?>