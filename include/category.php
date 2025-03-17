<?php

require_once "./Controller/DatabaseController.php";
require_once "./Controller/CategoriesController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

// $create_categories = new CategoriesController($conn);
// $create_categories->createCategories("");

$get_categories = new CategoriesController($conn);
$categories = $get_categories->getCategories();

?>

<div class="p-3 bg-gray-50 mb-3">
  <ul class="container m-auto flex items-center gap-5">
    <?php foreach ($categories as $category): ?>
      <li>
        <a class="text-[#2D3548] transition-all duration-500 hover:text-[#467AE9]" href="/category/<?= $category['slug']; ?>"> <?= $category['name']; ?> </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>