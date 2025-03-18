<?php

require_once "./Controller/DatabaseController.php";
require_once "./Controller/UsersController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

if (isset($_POST['logout'])) {
    $user->logoutUser();
    header("Location: /");
    exit();
}

?>
<div class="p-3 bg-white border border-b-gray-200 sticky top-0 z-50">
    <header class="flex items-center justify-between container m-auto relative">
        <a href="/" class="flex items-center gap-4 font-bold text-[#2D3548] text-xl">
            php
            <span class="px-2 py-1 text-xs bg-[#F2F3F5] text-[#2D3548] rounded-full">blog</span>
        </a>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="relative inline-block text-left">
                <button type="button" id="userMenuButton" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-lg font-medium text-gray-600">
                            <?= substr($_SESSION['user']['name'], 0, 1); ?>
                        </span>
                    </div>
                </button>

                <div id="userMenu" class="hidden absolute right-0 mt-2 w-60 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-3 px-4 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate"><?= $_SESSION['user']['name']; ?></p>
                        <p class="text-sm text-gray-500 truncate"><?= $_SESSION['user']['email']; ?></p>
                    </div>
                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Профиль
                    </a>
                    <?php if ($_SESSION['user']['role'] === "admin"): ?>
                        <a href="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Админ панель
                        </a>
                    <?php endif; ?>

                    <form action="/logout" method="post">
                        <button type="submit" name="logout" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-4">
                <a href="/sign-in" id="auth-btn" class="bg-[#2D3548] text-white rounded-xl px-4 py-2 transition-all duration-500 hover:bg-[#467AE9]/90">Войти</a>
            </div>
        <?php endif; ?>
    </header>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    });
</script>