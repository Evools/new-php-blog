<?php

$arr_link = [
  "html" => "HTML / CSS",
  "js" => "JS",
  "php" => "PHP",
  "web-master" => "Все для веб мастера",
];

?>

<div class="p-3">
  <ul class="container m-auto flex items-center gap-5">
    <?php foreach ($arr_link as $link => $name): ?>
      <li>
        <a class="text-[#2D3548] transition-all duration-500 hover:text-[#467AE9]" href="<?= $link; ?>"> <?= $name; ?> </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>