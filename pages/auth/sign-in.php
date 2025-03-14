<?php

require_once  "./Controller/DatabaseController.php";
require_once  "./Controller/UsersController.php";

if (isset($_SESSION['user'])) {
  header("Location: /");
  exit();
}

$error = [];

$data = $_POST;

if (isset($data['login'])) {
  $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
  $password = $data['password'] ?? '';

  if (empty($email)) {
    $error['email'] = "Поле Email не должно быть пустым";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error['email'] = "Неверный формат email";
  }

  if (empty($password)) {
    $error['password'] = "Поле пароля не должно быть пустым";
  }

  if (empty($error)) {
    $db = DatabaseController::getInstance();
    $conn = $db->getConnect();
    $user = new UserController($conn);

    if ($user->loginUser($email, $password)) {
      header("Location: /");
      exit();
    } else {
      $error['email'] = "Неверный логин или пароль";
    }
  }
}


?>

<?php $titleName = "Авторизация"; ?>
<?php include "layout/head.php"; ?>

<section class="bg-gray-50">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

    <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
      <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
          Авторизация
        </h1>
        <form class="space-y-4 md:space-y-6" action="/login" method="post">
          <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" placeholder="example@mail.com">
            <?php if (isset($error['email'])): ?>
              <small class="text-red-500"><?= $error['email'] ?></small>
            <?php endif; ?>
          </div>
          <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Пароль</label>
            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
            <?php if (isset($error['password'])): ?>
              <small class="text-red-500"><?= $error['password'] ?></small>
            <?php endif; ?>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:border-gray-600" required="">
              </div>
              <div class="ml-3 text-sm">
                <label for="remember" class="text-gray-500">Запомнить меня</label>
              </div>
            </div>
            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">Забыли пароль?</a>
          </div>
          <button name="login" type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Войти</button>
          <p class="text-sm font-light text-gray-500">
            У вас еще нет аккаунта? <a href="/sign-up" class="font-medium text-blue-600 hover:underline">Регистрация</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include "layout/footer.php"; ?>