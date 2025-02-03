<div class="p-3 bg-white border border-b-gray-200 sticky top-0">
    <header class="flex items-center justify-between container m-auto relative">
        <a href="/" class="flex items-center gap-4 font-bold text-[#2D3548] text-xl">
            php
            <span class="px-2 py-1 text-xs bg-[#F2F3F5] text-[#2D3548] rounded-full">blog</span>
        </a>
        <div class="flex items-center gap-4">
            <button id="auth-btn" class="bg-[#2D3548] text-white rounded-xl px-4 py-2 transition-all duration-500 hover:bg-[#467AE9]/90">Войти</button>
        </div>
        <div id="auth-modal" class="hidden flex items-center flex-col gap-3 max-w-[330px] w-full p-5 rounded-xl bg-white border border-gray-100 absolute top-16 right-0">
            <h2 class="text-bold text-xl">Авторизация</h2>
            <form class="flex items-center flex-col gap-3 w-full" action="" method="post">
                <input class="w-full border border-gray-200 rounded-lg px-3 py-2" type="email" placeholder="E-mail">
                <input class="w-full border border-gray-200 rounded-lg px-3 py-2" type="password" placeholder="Пароль">
                <button class="bg-[#2D3548] text-white rounded-xl px-4 py-2 transition-all duration-500 hover:bg-[#467AE9]/90 w-full">Авторизация</button>
                <a href="#" id="register-link" class="text-blue-500 underline">Регистрация</a>
            </form>
        </div>
        <div id="register-modal" class="hidden flex items-center flex-col gap-3 max-w-[330px] w-full p-5 rounded-xl bg-white border border-gray-100 absolute top-16 right-0">
            <h2 class="text-bold text-xl">Регистрация</h2>
            <form class="flex items-center flex-col gap-3 w-full" action="" method="post">
                <input class="w-full border border-gray-200 rounded-lg px-3 py-2" type="email" placeholder="E-mail">
                <input class="w-full border border-gray-200 rounded-lg px-3 py-2" type="password" placeholder="Пароль">
                <input class="w-full border border-gray-200 rounded-lg px-3 py-2" type="password" placeholder="Повторите пароль">
                <button class="bg-[#2D3548] text-white rounded-xl px-4 py-2 transition-all duration-500 hover:bg-[#467AE9]/90 w-full">Регистрация</button>
                <a href="#" id="login-link" class="text-blue-500 underline">Авторизация</a>
            </form>
        </div>
    </header>
</div>

<script>
    const authBtn = document.querySelector('#auth-btn');
    const authModal = document.querySelector('#auth-modal');
    const registerModal = document.querySelector('#register-modal');
    const registerLink = document.querySelector('#register-link');
    const loginLink = document.querySelector('#login-link');

    authBtn.addEventListener('click', () => {
        authModal.classList.toggle('hidden');
        registerModal.classList.add('hidden');
    });

    registerLink.addEventListener('click', (e) => {
        e.preventDefault();
        authModal.classList.add('hidden');
        registerModal.classList.remove('hidden');
    });

    loginLink.addEventListener('click', (e) => {
        e.preventDefault();
        registerModal.classList.add('hidden');
        authModal.classList.remove('hidden');
    });

    window.addEventListener('click', (e) => {
        if (!authModal.contains(e.target) && !authBtn.contains(e.target) && !registerModal.contains(e.target)) {
            authModal.classList.add('hidden');
            registerModal.classList.add('hidden');
        }
    });
</script>