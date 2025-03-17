<?php

$arr_links = [
  "Dashboard" => "/admin/",
  "Новости" => "/admin/news/",
  "Категории" => "/admin/categories/",
  "Пользователи" => "/admin/users/",
  "Рассылки" => "/admin/newsletters",
];

?>


<div class="p-3 bg-gray-50 mb-3">
  <ul class="container m-auto flex items-center gap-5">
    <?php foreach ($arr_links as $name => $link): ?>
      <a
        class="text-[#2D3548] transition-all duration-500 hover:text-[#467AE9]"
        href="<?= $link; ?>">
        <?= $name; ?>
      </a>
    <?php endforeach; ?>
  </ul>
</div>