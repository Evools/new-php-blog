<?php

require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();
$users = new UserController($conn);


$data = $_POST;

if (isset($data['add-user'])) {

  $name = $data['name'];
  $email = $data['email'];
  $password = $data['password'];
  $role = $data['role'];

  $error = [];

  if (empty($name)) {
    $error['name'] = 'Имя не должно быть пустым';
  }
  if (empty($email)) {
    $error['email'] = 'Email адресс не должен быть пустым';
  }
  if (empty($password)) {
    $error['password'] = 'Пароль не должен быть пустым';
  }
  if (empty($role)) {
    $error['role'] = 'Выберите роль пользователя';
  }

  if (empty($error)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $create_user = $users->createUser($name, $email, $password_hash, $role);

    if ($create_user) {
      header('Location: /admin/users');
      exit();
    } else {
      $error['general'] = 'Ошибка при создании пользователя';
    }
  }
}

?>


<?php $titleName = "Админ панель"; ?>
<?php include "layout/head.php"; ?>

<main>
  <?php include "admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Добавить пользователя</h1>
      <a href="/admin/users" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
        Назад
      </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <?php if (isset($error['general'])): ?>
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded flex items-center gap-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span><?= $error['general'] ?></span>
        </div>
      <?php endif; ?>

      <?php if (isset($error['name']) || isset($error['email']) || isset($error['password'])): ?>
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded">
          <div class="flex gap-3 mb-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">Пожалуйста, заполните следующие поля:</span>
          </div>
          <ul class="list-disc list-inside space-y-1 ml-5">
            <?php if (isset($error['name'])): ?>
              <li><?= $error['name'] ?></li>
            <?php endif; ?>
            <?php if (isset($error['email'])): ?>
              <li><?= $error['email'] ?></li>
            <?php endif; ?>
            <?php if (isset($error['password'])): ?>
              <li><?= $error['password'] ?></li>
            <?php endif; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if (isset($error['role'])): ?>
        <div class="mb-6 p-4 bg-orange-50 border border-orange-200 text-orange-800 rounded flex items-center gap-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <span><?= $error['role'] ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="/admin/users/create" class="space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
          <input type="text" name="name" id="name" value="<?= isset($data['name']) ? htmlspecialchars($data['name']) : '' ?>"
            class="mt-1 block w-full rounded-md border <?= isset($error['name']) ? 'border-red-300' : 'border-gray-300' ?> px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div class="mt-3">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" id="email" value="<?= isset($data['email']) ? htmlspecialchars($data['email']) : '' ?>"
            class="mt-1 block w-full rounded-md border <?= isset($error['email']) ? 'border-red-300' : 'border-gray-300' ?> px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div class="mt-3">
          <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
          <input type="password" name="password" id="password"
            class="mt-1 block w-full rounded-md border <?= isset($error['password']) ? 'border-red-300' : 'border-gray-300' ?> px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div class="mt-3">
          <label for="role" class="block text-sm font-medium text-gray-700">Роль</label>
          <div class="mt-1 grid grid-cols-2 gap-3">
            <div>
              <input type="radio" id="role_user" name="role" value="user" class="hidden peer">
              <label for="role_user" class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                  <span class="text-sm font-medium">Пользователь</span>
                </div>
              </label>
            </div>
            <div>
              <input type="radio" id="role_admin" name="role" value="admin" class="hidden peer">
              <label for="role_admin" class="inline-flex items-center justify-center w-full p-3 text-gray-700 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-gray-50">
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                    <path d="m9 12 2 2 4-4" />
                  </svg>
                  <span class="text-sm font-medium">Администратор</span>
                </div>
              </label>
            </div>
          </div>
          <p class="mt-1 text-sm text-gray-500">Выберите роль пользователя</p>
        </div>

        <div class="flex justify-end mt-5">
          <button name="add-user" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Добавить пользователя
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>

<?php if (isset($error['role'])): ?>
  <small class="text-red-500"><?= $error['role'] ?></small>
<?php endif; ?>

<?php if (isset($error['general'])): ?>
  <div class="mt-3 p-3 bg-red-100 text-red-700 rounded-lg">
    <?= $error['general'] ?>
  </div>
<?php endif; ?>