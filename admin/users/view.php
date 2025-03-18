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

      <div class="relative">
        <button id="roleDropdownButton" class="px-4 py-2 w-48 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 inline-flex items-center justify-between">
          <span id="selectedRole" class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-quote text-gray-500">
              <path d="M17 6H3" />
              <path d="M21 12H8" />
              <path d="M21 18H8" />
              <path d="M3 12v6" />
            </svg>
            Все роли
          </span>
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <div id="roleDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
          <div class="py-1">
            <a href="#" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer" data-value="">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-quote text-gray-500">
                <path d="M17 6H3" />
                <path d="M21 12H8" />
                <path d="M21 18H8" />
                <path d="M3 12v6" />
              </svg>
              Все роли
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer" data-value="admin">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check text-indigo-500">
                <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                <path d="m9 12 2 2 4-4" />
              </svg>
              Администратор
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer" data-value="user">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user text-red-500">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              Пользователь
            </a>
          </div>
        </div>
      </div>
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
                    <a href="/admin/users/edit/<?= $user['id'] ?>" class="text-blue-600 hover:text-blue-900">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>
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
  const itemsPerPage = 10;
  let currentPage = 1;
  let filteredUsers = [];
  let selectedValue = '';

  function getAllUsers() {
    return Array.from(document.querySelectorAll('#usersTableBody tr'));
  }

  function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const allUsers = getAllUsers();

    if (searchTerm === '' && selectedValue === '') {
      filteredUsers = allUsers;
    } else {
      filteredUsers = allUsers.filter(user => {
        const userName = user.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const userEmail = user.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const userRole = user.dataset.role;

        const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
        const matchesRole = selectedValue === '' || userRole === selectedValue;

        return matchesSearch && matchesRole;
      });
    }

    updatePagination();
    showPage(1);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.getElementById('roleDropdownButton');
    const dropdown = document.getElementById('roleDropdown');
    const selectedRoleSpan = document.getElementById('selectedRole');

    dropdownButton.addEventListener('click', () => {
      dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!dropdownButton.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });

    dropdown.querySelectorAll('a').forEach(option => {
      option.addEventListener('click', (e) => {
        e.preventDefault();
        selectedValue = e.target.dataset.value;
        selectedRoleSpan.textContent = e.target.textContent;
        dropdown.classList.add('hidden');
        filterUsers();
      });
    });

    document.getElementById('searchInput').addEventListener('input', filterUsers);

    document.getElementById('prevPage').addEventListener('click', () => showPage(currentPage - 1));
    document.getElementById('nextPage').addEventListener('click', () => showPage(currentPage + 1));

    filteredUsers = getAllUsers();
    updatePagination();
    showPage(1);
  });

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

  function showPage(pageNum) {
    currentPage = pageNum;
    const start = (pageNum - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    getAllUsers().forEach(user => user.style.display = 'none');

    filteredUsers.slice(start, end).forEach(user => user.style.display = '');

    updatePagination();
  }
</script>