<?php $titleName = "Главная"; ?>
<?php include "layout/head.php"; ?>


<main>
  <?php include "layout/nav.php"; ?>
  <?php include "include/category.php"; ?>

  <div class="container m-auto">
    <h2 class="text-2xl">Новости</h2>
    <div class="flex items-start gap-2 justify-between">
      <div>
        <div>
          <div>
            <img src="" alt="">
          </div>
          <div>
            <div>
              <p>#How to</p>
              <p>#редактирование фото</p>
            </div>
            <h2>
              Как сделать генеалогическое дерево с семейными фотографиями
            </h2>
            <div>
              <span>9 минут</span>
              <span>21.12.2022</span>
            </div>
          </div>
        </div>
      </div>
      <div>
        <h2>Подпишитесь на новые посты</h2>
        <input type="email" placeholder="E-mail">
        <div>
          <input type="checkbox" name="" id="">
          <p>
            Подписываясь на рассылку, вы соглашаетесь на получение от нас рекламной информации по электронной почте и обработку персональных данных в соответсвии с <a href="#">Политикой конфиденциальности.</a>
          </p>
        </div>
      </div>
    </div>
  </div>

</main>

<?php include "layout/footer.php"; ?>