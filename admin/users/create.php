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

  // if (empty($error)) {
  //   $create_user = $user->createUser($name, $email, $password, $role = 'user');
  // }
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
      <form method="POST" action="/admin/users/create" class="space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
          <input type="text" name="name" id="name"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <?php if (isset($error['name'])): ?>
            <small class="text-red-500"><?= $error['name'] ?></small>
          <?php endif; ?>
        </div>

        <div class="mt-3">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" id="email"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <?php if (isset($error['email'])): ?>
            <small class="text-red-500"><?= $error['email'] ?></small>
          <?php endif; ?>
        </div>

        <div class="mt-3">
          <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
          <input type="password" name="password" id="password"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <?php if (isset($error['password'])): ?>
            <small class="text-red-500"><?= $error['password'] ?></small>
          <?php endif; ?>
        </div>

        <div class="mt-3">
          <label for="role" class="block text-sm font-medium text-gray-700">Роль</label>
          <select name="role" id="role"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Выберите роль</option>
            <option value="admin">Администратор</option>
            <option value="user">Пользователь</option>
          </select>
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