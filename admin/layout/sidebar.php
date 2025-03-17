<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button id="toggleSidebarMobile" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
          </svg>
        </button>
        <a href="/" class="flex ms-2 md:me-24">
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">Admin Panel</span>
        </a>
      </div>
      <div class="flex items-center">
        <div class="flex items-center ms-3">
          <div>
            <button id="toggleUserMenu" type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300">
              <span class="sr-only">Open user menu</span>
              <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
            </button>
          </div>
          <div id="userDropdown" class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow absolute right-4 top-12">
            <div class="px-4 py-3">
              <p class="text-sm text-gray-900">
                <?= $_SESSION['user']['name'] ?? 'User Name' ?>
              </p>
              <p class="text-sm font-medium text-gray-900 truncate">
                <?= $_SESSION['user']['email'] ?? 'email@example.com' ?>
              </p>
            </div>
            <ul class="py-1">
              <li>
                <a href="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
              </li>
              <li>
                <form action="/logout" method="post">
                  <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>

<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0">
  <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
    <ul class="space-y-2 font-medium">
      <li>
        <a href="/admin/" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
          <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
          </svg>
          <span class="ms-3">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="/admin/categories/" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
          <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z" />
          </svg>
          <span class="flex-1 ms-3 whitespace-nowrap">Категории</span>
          <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">6</span>
        </a>
      </li>
      <li>
        <a href="/admin/users/" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
          <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
            <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
          </svg>
          <span class="flex-1 ms-3 whitespace-nowrap">Пользователи</span>
        </a>
      </li>
    </ul>
  </div>
</aside>

<script>
  // Toggle User Dropdown
  const userButton = document.getElementById('toggleUserMenu');
  const userDropdown = document.getElementById('userDropdown');

  userButton.addEventListener('click', () => {
    userDropdown.classList.toggle('hidden');
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) {
      userDropdown.classList.add('hidden');
    }
  });

  // Mobile Sidebar Toggle
  const toggleSidebarMobile = document.getElementById('toggleSidebarMobile');
  const sidebar = document.getElementById('sidebar');

  toggleSidebarMobile.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
  });

  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', (e) => {
    if (window.innerWidth < 640 && // Only on mobile
      !toggleSidebarMobile.contains(e.target) &&
      !sidebar.contains(e.target) &&
      !sidebar.classList.contains('-translate-x-full')) {
      sidebar.classList.add('-translate-x-full');
    }
  });
</script>