<?php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: /");
  exit();
}

?>


<?php $titleName = "Админ панель"; ?>
<?php include "layout/head.php"; ?>


<main>
  <?php include "layout/nav.php"; ?>
  <?php include "admin/layout/nav.php"; ?>

  <div class="container m-auto">
    <?php include "admin/include/category.php"; ?>
  </div>
</main>

<?php include "layout/footer.php"; ?>