<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);

$titleName = "Профиль";
include "layout/head.php";
?>
<?php include "layout/nav.php"; ?>
<main class="bg-gray-50 min-h-screen pt-16">
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Настройки профиля</h1>

      <!-- Tabs -->
      <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
          <button onclick="switchTab('profile')"
            class="tab-button active-tab border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2"
            data-tab="profile">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user">
              <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            Информация профиля
          </button>
          <button onclick="switchTab('security')"
            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2"
            data-tab="security">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-user">
              <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
              <path d="M6.376 18.91a6 6 0 0 1 11.249.003" />
              <circle cx="12" cy="11" r="4" />
            </svg>
            Безопасность
          </button>
        </nav>
      </div>

      <!-- Profile Tab Content -->
      <div id="profile-tab" class="tab-content">
        <div class="bg-white shadow rounded-lg p-6">
          <form method="POST" action="/profile/update" class="space-y-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
              <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-1 py-2">
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" id="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-1 py-2">
            </div>

            <div>
              <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Сохранить изменения
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Tab Content -->
      <div id="security-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg p-6">
          <form method="POST" action="/profile/change-password" class="space-y-6">
            <div>
              <label for="current_password" class="block text-sm font-medium text-gray-700">Текущий пароль</label>
              <input type="password" name="current_password" id="current_password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-1 py-2">
            </div>

            <div>
              <label for="new_password" class="block text-sm font-medium text-gray-700">Новый пароль</label>
              <input type="password" name="new_password" id="new_password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-1 py-2">
            </div>

            <div>
              <label for="confirm_password" class="block text-sm font-medium text-gray-700">Подтвердите новый пароль</label>
              <input type="password" name="confirm_password" id="confirm_password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-1 py-2">
            </div>

            <div>
              <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Изменить пароль
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
      tab.classList.add('hidden');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Update tab button styles
    document.querySelectorAll('.tab-button').forEach(button => {
      button.classList.remove('border-blue-500', 'text-blue-600');
      button.classList.add('border-transparent', 'text-gray-500');
    });

    // Style active tab button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-blue-500', 'text-blue-600');
  }
</script>

<?php include "layout/footer.php"; ?>