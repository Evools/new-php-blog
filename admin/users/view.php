<?php

require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);

$get_all_users = $users->getAllUsers();

?>


<?php $titleName = "Админ панель"; ?>
<?php include "layout/head.php"; ?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Пользователи</h1>
      <a href="/admin/users/create/" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        Добавить пользователя
      </a>
    </div>

    <div class="mb-4 flex gap-4">
      <div class="flex-1">
        <input type="text" id="searchInput" placeholder="Поиск пользователей..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
      </div>
      <select id="roleFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Все роли</option>
        <option value="admin">Администратор</option>
        <option value="user">Пользователь</option>
      </select>
    </div>

    <div class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3">ID</th>
              <th scope="col" class="px-6 py-3">Имя</th>
              <th scope="col" class="px-6 py-3">Email</th>
              <th scope="col" class="px-6 py-3">Роль</th>
              <th scope="col" class="px-6 py-3">Дата регистрации</th>
              <th scope="col" class="px-6 py-3">Действия</th>
            </tr>
          </thead>

          <tbody id="usersTableBody">
            <?php foreach ($get_all_users as $user): ?>
              <tr class="bg-white border-b hover:bg-gray-50" data-role="<?= $user['role']; ?>">
                <td class="px-6 py-4"><?= $user['id']; ?></td>
                <td class="px-6 py-4 font-medium text-gray-900"><?= $user['name']; ?></td>
                <td class="px-6 py-4"><?= $user['email']; ?></td>
                <td class="px-6 py-4">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    <?= $user['role']; ?>
                  </span>
                </td>
                <td class="px-6 py-4"><?= $user['created_at']; ?></td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <a href="/admin/users/edit/" class="text-blue-600 hover:text-blue-900">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>
                    <!-- Update the table row button -->
                    <!-- In the table row -->
                    <button onclick="openDeleteModal(<?= $user['id']; ?>, '<?= htmlspecialchars($user['name']); ?>')" class="text-red-600 hover:text-red-900">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>

                    <div id="deleteUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3 text-center">
                          <svg class="mx-auto mb-4 w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                          </svg>
                          <h3 class="text-lg font-medium text-gray-900">Удалить пользователя?</h3>
                          <div class="mt-2 px-7 py-3">
                            <p class="text-gray-500">Вы действительно хотите удалить пользователя <span id="deleteUserName"></span>?</p>
                          </div>
                          <div class="flex justify-center gap-3 mt-3">
                            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                              Отмена
                            </button>
                            <form method="POST" action="/admin/users/delete">
                              <input type="hidden" name="delete_user" id="deleteUserId">
                              <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Удалить
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                    <script>
                      function openDeleteModal(userId, userName) {
                        document.getElementById('deleteUserName').textContent = userName;
                        document.getElementById('deleteUserId').value = userId;
                        document.getElementById('deleteUserModal').classList.remove('hidden');
                      }

                      function closeDeleteModal() {
                        document.getElementById('deleteUserModal').classList.add('hidden');
                      }
                    </script>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div id="deleteUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3 text-center">
            <svg class="mx-auto mb-4 w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Удалить пользователя?</h3>
            <div class="mt-2 px-7 py-3">
              <p class="text-gray-500">Вы действительно хотите удалить пользователя <span id="deleteUserName"></span>?</p>
            </div>
            <div class="flex justify-center gap-3 mt-3">
              <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Отмена
              </button>
              <form method="POST" action="">
                <input type="hidden" name="delete_user" id="deleteUserId">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                  Удалить
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Remove all delete-related JavaScript and keep only this -->
      <script>
        function openDeleteModal(userId, userName) {
          document.getElementById('deleteUserName').textContent = userName;
          document.getElementById('deleteUserId').value = userId;
          document.getElementById('deleteUserModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
          document.getElementById('deleteUserModal').classList.add('hidden');
        }
      </script>

      <!-- Pagination -->
      <div class="flex items-center justify-between p-4 border-t">
        <div class="text-sm text-gray-700">
          Показано <span id="showingFrom">1</span>-<span id="showingTo">15</span> из <span id="totalItems">0</span>
        </div>
        <div class="flex gap-2">
          <button id="prevPage" class="px-3 py-1 text-sm border rounded hover:bg-gray-50">Назад</button>
          <div id="pageNumbers" class="flex gap-2"></div>
          <button id="nextPage" class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Вперед</button>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>


<script>
  const itemsPerPage = 15;
  let currentPage = 1;
  let filteredUsers = [];

  // Получить всех пользователей из таблицы
  function getAllUsers() {
    return Array.from(document.querySelectorAll('#usersTableBody tr'));
  }

  // Функция фильтра и поиска
  function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const allUsers = getAllUsers();

    // Инициализируем filteredUsers со всеми пользователями, если фильтры не применены
    if (searchTerm === '' && roleFilter === '') {
      filteredUsers = allUsers;
    } else {
      filteredUsers = allUsers.filter(user => {
        const userName = user.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const userEmail = user.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const userRole = user.dataset.role;

        const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
        const matchesRole = roleFilter === '' || userRole === roleFilter;

        return matchesSearch && matchesRole;
      });
    }

    updatePagination();
    showPage(1);
  }

  // Инициализация при загрузке DOM
  document.addEventListener('DOMContentLoaded', () => {
    filteredUsers = getAllUsers(); // Инициализация со всеми пользователями
    updatePagination();
    showPage(1);
  });

  // Обновить информацию о пагинации
  function updatePagination() {
    const total = filteredUsers.length;
    const totalPages = Math.ceil(total / itemsPerPage);

    document.getElementById('totalItems').textContent = total;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;

    const from = ((currentPage - 1) * itemsPerPage) + 1;
    const to = Math.min(currentPage * itemsPerPage, total);

    document.getElementById('showingFrom').textContent = from;
    document.getElementById('showingTo').textContent = to;

    // Update page numbers
    const pageNumbers = document.getElementById('pageNumbers');
    pageNumbers.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
      const pageButton = document.createElement('button');
      pageButton.className = `px-3 py-1 text-sm rounded ${
        i === currentPage 
          ? 'bg-blue-600 text-white' 
          : 'border hover:bg-gray-50'
      }`;
      pageButton.textContent = i;
      pageButton.onclick = () => showPage(i);
      pageNumbers.appendChild(pageButton);
    }
  }

  // Initialize immediately when DOM loads
  document.addEventListener('DOMContentLoaded', () => {
    filteredUsers = getAllUsers();
    showPage(1); // This will also call updatePagination
  });

  // Показать определенную страницу
  function showPage(pageNum) {
    currentPage = pageNum;
    const start = (pageNum - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    // Скрыть всех пользователей
    getAllUsers().forEach(user => user.style.display = 'none');

    // Показывать только пользователей для текущей страницы
    filteredUsers.slice(start, end).forEach(user => user.style.display = '');

    updatePagination();
  }

  document.getElementById('searchInput').addEventListener('input', filterUsers);
  document.getElementById('roleFilter').addEventListener('change', filterUsers);
  document.getElementById('prevPage').addEventListener('click', () => showPage(currentPage - 1));
  document.getElementById('nextPage').addEventListener('click', () => showPage(currentPage + 1));

  filterUsers();
</script>