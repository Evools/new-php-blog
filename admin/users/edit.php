<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);

// Get user ID from URL parameter
// Replace the user ID retrieval line with this
$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$userId = (int)end($urlParts);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = (int)$_POST['user_id'];
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $role = $_POST['role'];
  $newPassword = trim($_POST['new_password']);

  try {
    if (empty($name) || empty($email)) {
      throw new Exception("Имя и email обязательны для заполнения");
    }

    // If password is empty, don't update it
    if (empty($newPassword)) {
      $users->updateUserWithoutPassword($userId, $name, $email, $role);
    } else {
      $users->updateUserWithPassword($userId, $name, $email, $newPassword, $role);
    }

    $_SESSION['success_message'] = "Данные пользователя успешно обновлены";
  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
  }
}

// Get user data after possible update
try {
  $user = $users->getUserById($userId);
  if (!$user) {
    $_SESSION['error_message'] = "Пользователь не найден";
    header('Location: /admin/users');
    exit();
  }
} catch (Exception $e) {
  $_SESSION['error_message'] = $e->getMessage();
  header('Location: /admin/users');
  exit();
}

$titleName = "Редактирование пользователя";
include "layout/head.php";
?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Редактирование пользователя</h1>
      <p class="mt-1 text-sm text-gray-600">Измените данные пользователя <?= htmlspecialchars($user['name']) ?></p>
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
      <form method="POST" action="/admin/users/edit/<?= $userId ?>" class="space-y-6">
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          </div>

          <div>
            <label for="role" class="block text-sm font-medium text-gray-700">Роль</label>
            <div class="mt-1 grid grid-cols-2 gap-3">
              <div>
                <input type="radio" id="role_user" name="role" value="user" <?= $user['role'] === 'user' ? 'checked' : '' ?> class="hidden peer">
                <label for="role_user" class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                  <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user">
                      <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                      <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="text-sm font-medium">Пользователь</span>
                  </div>
                </label>
              </div>
              <div>
                <input type="radio" id="role_admin" name="role" value="admin" <?= $user['role'] === 'admin' ? 'checked' : '' ?> class="hidden peer">
                <label for="role_admin" class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                  <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                      <path d="m9 12 2 2 4-4" />
                    </svg>
                    <span class="text-sm font-medium">Администратор</span>
                  </div>
                </label>
              </div>
            </div>
            <p class="mt-1 text-sm text-gray-500">Можете изменить роль</p>
          </div>

          <div>
            <label for="new_password" class="block text-sm font-medium text-gray-700">Новый пароль</label>
            <input type="password" name="new_password" id="new_password"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p class="mt-1 text-sm text-gray-500">Оставьте пустым, чтобы не менять пароль</p>
          </div>
        </div>

        <div class="flex gap-3">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Сохранить изменения
          </button>
          <a href="/admin/users" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
            Отмена
          </a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>