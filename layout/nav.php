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
<div class="p-3 bg-white border border-b-gray-200 sticky top-0">
    <header class="flex items-center justify-between container m-auto relative">
        <a href="/" class="flex items-center gap-4 font-bold text-[#2D3548] text-xl">
            php
            <span class="px-2 py-1 text-xs bg-[#F2F3F5] text-[#2D3548] rounded-full">blog</span>
        </a>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="flex items-center gap-4">
                <p class="font-medium"><?= $_SESSION['user']['name']; ?></p>
                <form action="/logout" method="post">
                    <button name="logout" type="submit" id="auth-btn" class="bg-[#2D3548] text-white rounded-xl px-4 py-2 transition-all duration-500 hover:bg-[#467AE9]/90">Выйти</button>
                </form>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-4">
                <a href="/sign-in" id="auth-btn" class="bg-[#2D3548] text-white rounded-xl px-4 py-2 transition-all duration-500 hover:bg-[#467AE9]/90">Войти</a>
            </div>
        <?php endif; ?>

    </header>
</div>