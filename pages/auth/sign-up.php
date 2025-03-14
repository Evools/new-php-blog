<?php $titleName = "Авторизация"; ?>
<?php include "layout/head.php"; ?>

<?php

require_once  "./Controller/DatabaseController.php";
require_once  "./Controller/UsersController.php";

if (isset($_SESSION['user'])) {
  header("Location: /");
  exit();
}

$error = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $name = "Username";
  $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';

  if (empty($email)) {
    $error['email'] = "Поле Email не должно быть пустым";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error['email'] = "Неверный формат email";
  }

  if (empty($password)) {
    $error['password'] = "Поле пароля не должно быть пустым";
  } elseif (strlen($password) < 8) {
    $error['password'] = "Пароль должен содержать минимум 8 символов";
  } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
    $error['password'] = "Пароль должен содержать хотя бы одну заглавную букву и цифру";
  }

  if (empty($confirm)) {
    $error['confirm'] = "Поле повторного пароля не должно быть пустым";
  } elseif ($password !== $confirm) {
    $error['confirm'] = "Пароли не совпадают";
  }

  if (empty($error)) {
    $db = DatabaseController::getInstance();
    $conn = $db->getConnect();
    $user = new UserController($conn);

    if ($user->emailExists($email)) {
      $error['email'] = "Этот email уже зарегистрирован";
    } else {
      $passwordHash = password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 2048,
        'time_cost' => 4,
        'threads' => 3
      ]);

      $user->createUser($name, $email, $passwordHash, "user");
      header("Location: /sign-in");
      exit();
    }
  }
}


?>

<section class="bg-gray-50">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

    <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
      <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
          Регистрация
        </h1>
        <form class="space-y-4 md:space-y-6" action="sign-up" method="post">
          <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 <?= isset($error['email']) ? 'border-red-500' : '' ?>" placeholder="example@mail.com">
            <?php if (isset($error['email'])): ?>
              <small class="text-red-500"><?= $error['email'] ?></small>
            <?php endif; ?>
          </div>
          <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Пароль</label>
            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 <?= isset($error['password']) ? 'border-red-500' : '' ?>">
            <?php if (isset($error['password'])): ?>
              <small class="text-red-500"><?= $error['password'] ?></small>
            <?php endif; ?>
          </div>
          <div>
            <label for="confirm" class="block mb-2 text-sm font-medium text-gray-900">Подтверждение пароля</label>
            <input type="password" name="confirm" id="confirm" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 <?= isset($error['confirm']) ? 'border-red-500' : '' ?>">
            <?php if (isset($error['confirm'])): ?>
              <small class="text-red-500"><?= $error['confirm'] ?></small>
            <?php endif; ?>
          </div>

          <button type="submit" name="register" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Регистрация</button>
          <p class="text-sm font-light text-gray-500">
            У вас уже есть аккаунт? <a href="/sign-in" class="font-medium text-blue-600 hover:underline">Войти</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include "layout/footer.php"; ?>