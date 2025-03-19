<?php $titleName = "Главная"; ?>
<?php include "layout/head.php"; ?>


<main>
  <?php include "layout/nav.php"; ?>
  <?php include "include/category.php"; ?>

  <div class="container m-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Новости</h2>
    <div class="flex flex-col lg:flex-row items-start gap-8">
      <div class="w-full flex flex-col gap-6">
        <article class="flex items-center gap-2">
          <div class="w-[280px] h-[175px] rounded-2xl overflow-hidden">
            <img src="/assets/img/post.png" alt="Генеалогическое дерево" class="object-cover w-full h-full">
          </div>
          <div class="p-4">
            <div class="flex gap-2 mb-3">
              <span class="text-sm text-[#8B92A1] font-medium">#How to</span>
              <span class="text-sm text-[#8B92A1] font-medium">#редактирование фото</span>
            </div>
            <h3 class="text-xl font-semibold mb-3 hover:text-blue-600 transition-colors">
              <a href="#">Как сделать генеалогическое дерево с семейными фотографиями</a>
            </h3>
            <div class="flex items-center text-gray-500 text-sm">
              <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>9 минут</span>
              <span class="mx-2">•</span>
              <span>21.12.2022</span>
            </div>
          </div>
        </article>

        <article class="flex items-center gap-2">
          <div class="w-[280px] h-[175px] rounded-2xl overflow-hidden">
            <img src="/assets/img/post-2.png" alt="Генеалогическое дерево" class="object-cover w-full h-full">
          </div>
          <div class="p-4">
            <div class="flex gap-2 mb-3">
              <span class="text-sm text-[#8B92A1] font-medium">#How to</span>
              <span class="text-sm text-[#8B92A1] font-medium">#редактирование фото</span>
            </div>
            <h3 class="text-xl font-semibold mb-3 hover:text-blue-600 transition-colors">
              <a href="#">Как сделать генеалогическое дерево с семейными фотографиями</a>
            </h3>
            <div class="flex items-center text-gray-500 text-sm">
              <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>9 минут</span>
              <span class="mx-2">•</span>
              <span>21.12.2022</span>
            </div>
          </div>
        </article>

        <article class="flex items-center gap-2">
          <div class="w-[280px] h-[175px] rounded-2xl overflow-hidden">
            <img src="/assets/img/post-3.png" alt="Генеалогическое дерево" class="object-cover w-full h-full">
          </div>
          <div class="p-4">
            <div class="flex gap-2 mb-3">
              <span class="text-sm text-[#8B92A1] font-medium">#How to</span>
              <span class="text-sm text-[#8B92A1] font-medium">#редактирование фото</span>
            </div>
            <h3 class="text-xl font-semibold mb-3 hover:text-blue-600 transition-colors">
              <a href="#">Как сделать генеалогическое дерево с семейными фотографиями</a>
            </h3>
            <div class="flex items-center text-gray-500 text-sm">
              <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>9 минут</span>
              <span class="mx-2">•</span>
              <span>21.12.2022</span>
            </div>
          </div>
        </article>

      </div>

      <div class="w-full lg:w-1/3 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Подпишитесь на новые посты</h2>
        <form class="space-y-4">
          <div>
            <input type="email" placeholder="E-mail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
          <div class="flex items-start gap-2">
            <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <p class="text-sm text-gray-600">
              Подписываясь на рассылку, вы соглашаетесь на получение от нас рекламной информации по электронной почте и обработку персональных данных в соответствии с <a href="#" class="text-blue-600 hover:underline underline">Политикой конфиденциальности</a>.
            </p>
          </div>
          <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">Подписаться</button>
        </form>
      </div>
    </div>
  </div>
</main>

<?php include "layout/footer.php"; ?>