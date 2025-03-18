<?php
if (!isset($_SESSION['user'])) {
  header('Location: /login');
  exit();
}

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
      <!-- Profile Header -->
      <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="flex items-center space-x-6">
          <div class="relative">
            <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
              <?php if (isset($_SESSION['user']['avatar'])): ?>
                <img src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>" alt="Profile" class="w-full h-full object-cover">
              <?php else: ?>
                <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z" />
                </svg>
              <?php endif; ?>
            </div>
            <label for="avatar-upload" class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-2 cursor-pointer hover:bg-blue-700 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
              </svg>
              <input type="file" id="avatar-upload" class="hidden" accept="image/*">
            </label>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
            <p class="text-gray-500"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="mb-6 bg-white rounded-lg shadow">
        <nav class="flex space-x-8 p-4" aria-label="Tabs">
          <button onclick="switchTab('profile')"
            class="tab-button active-tab text-blue-600 whitespace-nowrap py-2 px-4 rounded-lg font-medium text-sm flex items-center gap-2 bg-blue-50"
            data-tab="profile">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user">
              <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            Информация профиля
          </button>
          <button onclick="switchTab('security')"
            class="tab-button text-gray-500 hover:text-gray-700 whitespace-nowrap py-2 px-4 rounded-lg font-medium text-sm flex items-center gap-2 hover:bg-gray-50"
            data-tab="security">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
              <path d="m9 12 2 2 4-4" />
            </svg>
            Безопасность
          </button>
        </nav>
      </div>

      <!-- Profile Tab Content -->
      <div id="profile-tab" class="tab-content">
        <div class="bg-white shadow rounded-lg p-6">
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

          <form method="POST" action="/profile/update" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
                  class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>"
                  class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              </div>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h-2v5.586l-1.293-1.293z" />
                </svg>
                Сохранить изменения
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Tab Content -->
      <div id="security-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg p-6">
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

          <form method="POST" action="/profile/update-password" class="space-y-6">
            <div class="space-y-4">
              <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Текущий пароль</label>
                <input type="password" name="current_password" id="current_password"
                  class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              </div>

              <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Новый пароль</label>
                <input type="password" name="new_password" id="new_password"
                  class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              </div>

              <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Подтвердите новый пароль</label>
                <input type="password" name="confirm_password" id="confirm_password"
                  class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              </div>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h-2v5.586l-1.293-1.293z" />
                </svg>
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
    document.querySelectorAll('.tab-content').forEach(tab => {
      tab.classList.add('hidden');
    });

    document.getElementById(tabName + '-tab').classList.remove('hidden');

    document.querySelectorAll('.tab-button').forEach(button => {
      button.classList.remove('text-blue-600', 'bg-blue-50');
      button.classList.add('text-gray-500');
    });

    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.remove('text-gray-500');
    activeButton.classList.add('text-blue-600', 'bg-blue-50');
  }

  document.getElementById('avatar-upload').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.querySelector('.w-32.h-32 img') || document.createElement('img');
        preview.src = e.target.result;
        preview.classList.add('w-full', 'h-full', 'object-cover');
        const container = document.querySelector('.w-32.h-32');
        container.innerHTML = '';
        container.appendChild(preview);
      }
      reader.readAsDataURL(e.target.files[0]);
    }
  });
</script>

<?php include "layout/footer.php"; ?>