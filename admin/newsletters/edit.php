<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/NewsletterController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

// Change how we get the ID from the URL
$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$id = end($urlParts);
$id = (int)$id;

$newsletter = new NewsletterController($conn);
$newsletterData = $newsletter->getNewsletterById($id);

if (!$newsletterData) {
  header("Location: /admin/newsletters");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $subject = $_POST['subject'] ?? '';
  $content = $_POST['content'] ?? '';

  if (empty($subject) || empty($content)) {
    $error = "Заполните все поля";
  } else {
    try {
      if ($newsletter->updateNewsletter($id, $subject, $content)) {
        $_SESSION['message'] = 'Рассылка успешно обновлена';
        $_SESSION['message_type'] = 'success';
        header("Location: /admin/newsletters");
        exit;
      }
    } catch (Exception $e) {
      $error = $e->getMessage();
    }
  }
}

$titleName = "Редактирование рассылки";
include "./layout/head.php";
?>
<?php include "./admin/layout/sidebar.php"; ?>
<div class="p-4 sm:ml-64 mt-16">
  <div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Редактирование рассылки</h3>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>

    <form action="/admin/newsletters/edit/<?php echo $id; ?>" method="POST" class="mt-8">
      <div class="space-y-6">
        <div>
          <label for="subject" class="text-sm text-gray-700">Тема письма</label>
          <input type="text" name="subject" id="subject" required
            value="<?php echo htmlspecialchars($newsletterData['subject']); ?>"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div>
          <label for="content" class="text-sm text-gray-700">Содержание письма</label>
          <div class="mt-1">
            <script src="https://cdn.tiny.cloud/1/cdrkywnx15k19w5nx0mss3ocsfzfdmgxzqdtg0x9b4oxiq3b/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

            <script>
              tinymce.init({
                selector: '#content',
                plugins: 'link image lists table code preview searchreplace autolink directionality emoticons',
                toolbar: 'undo redo | blocks | bold italic | link image table emoticons | numlist bullist | alignleft aligncenter alignright | code preview | removeformat',
                menubar: false,
                height: 400,
                branding: false,
                statusbar: true,
                paste_data_images: true,
                image_advtab: true,
                setup: function(editor) {
                  editor.on('init', function() {
                    editor.setContent(<?php echo json_encode($newsletterData['content']); ?>);
                  });
                },
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; font-size: 14px; line-height: 1.6; }'
              });
            </script>
            <textarea name="content" id="content"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($newsletterData['content']); ?></textarea>
          </div>
          <div class="mt-4">
            <button type="button" onclick="previewNewsletter()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200">
              <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Предпросмотр
            </button>
          </div>
        </div>
        <script>
          function previewNewsletter() {
            const content = tinymce.get('content').getContent();
            const subject = document.getElementById('subject').value;
            document.getElementById('preview-content').innerHTML = `
              <h1 class="text-2xl font-bold text-gray-900 mb-4">${subject}</h1>
              <div class="prose max-w-none">${content}</div>
            `;
            document.getElementById('preview-modal').classList.remove('hidden');
          }

          function closePreviewModal() {
            document.getElementById('preview-modal').classList.add('hidden');
          }
        </script>

        <div class="flex justify-end space-x-4">
          <a href="/admin/newsletters" class="bg-gray-200 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-300">Отмена</a>
          <button type="submit" onclick="return validateForm()" class="bg-indigo-600 px-4 py-2 rounded-md text-white hover:bg-indigo-500">Сохранить</button>
        </div>

        <script>
          function validateForm() {
            const content = tinymce.get('content').getContent();
            if (!content) {
              alert('Пожалуйста, заполните содержание письма');
              return false;
            }
            return true;
          }
        </script>
      </div>
    </form>
  </div>

  <!-- Модальное окно для предпросмотра -->
  <div id="preview-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-[800px] max-h-[90vh] flex flex-col">
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="text-lg font-medium text-gray-900">Предпросмотр рассылки</h3>
        <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-500">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div id="preview-content" class="overflow-y-auto p-6 flex-1"></div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
        <button type="button" onclick="closePreviewModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Закрыть</button>
      </div>
    </div>
  </div>
</div>