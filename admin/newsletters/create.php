<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/NewsletterController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

$newsletter = new NewsletterController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $subject = $_POST['subject'] ?? '';
  $content = $_POST['content'] ?? '';

  if (empty($subject) || empty($content)) {
    $error = "Заполните все поля";
  } else {
    try {
      if ($newsletter->createNewsletter($subject, $content)) {
        header("Location: /admin/newsletters");
        exit;
      }
    } catch (Exception $e) {
      $error = $e->getMessage();
    }
  }
}

$titleName = "Создание рассылки";
include "./layout/head.php";
?>
<?php include "./admin/layout/sidebar.php"; ?>
<div class="p-4 sm:ml-64 mt-16">
  <div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Создание рассылки</h3>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-sm p-6 mt-4">
      <div class="flex items-center mb-4">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-4">
          <div id="progress-bar" class="bg-indigo-600 h-2.5 rounded-full" style="width: 0%"></div>
        </div>
        <span id="progress-text" class="text-sm text-gray-600">0%</span>
      </div>

      <form id="newsletter-form" action="/admin/newsletters/create" method="POST" class="mt-4">
        <div class="space-y-6">
          <div>
            <label for="subject" class="text-sm text-gray-700 flex items-center">
              <span>Тема письма</span>
              <div class="group relative ml-2">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="hidden group-hover:block absolute z-10 w-64 p-2 bg-gray-800 text-white text-sm rounded-lg mt-1">
                  Используйте короткую, но информативную тему, которая привлечет внимание получателей
                </div>
              </div>
            </label>
            <input type="text" name="subject" id="subject" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
              placeholder="Введите тему письма...">
          </div>

          <div>
            <label for="content" class="text-sm text-gray-700 flex items-center">
              <span>Содержание письма</span>
              <div class="group relative ml-2">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="hidden group-hover:block absolute z-10 w-64 p-2 bg-gray-800 text-white text-sm rounded-lg mt-1">
                  Создайте привлекательное и информативное содержание с помощью встроенного редактора
                </div>
              </div>
            </label>
            <div class="mt-1">
              <script src="https://cdn.tiny.cloud/1/cdrkywnx15k19w5nx0mss3ocsfzfdmgxzqdtg0x9b4oxiq3b/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

              <script>
                tinymce.init({
                  selector: '#content', // Changed from 'textarea' to '#content'
                  plugins: 'link image lists table code preview searchreplace autolink directionality emoticons',
                  toolbar: 'undo redo | blocks | bold italic | link image table emoticons | numlist bullist | alignleft aligncenter alignright | code preview | removeformat',
                  menubar: false,
                  height: 400,
                  branding: false,
                  statusbar: true,
                  paste_data_images: true,
                  image_advtab: true,
                  setup: function(editor) {
                    editor.on('input', function() {
                      updateProgress();
                    });
                  },
                  content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; font-size: 14px; line-height: 1.6; }'
                });
              </script>
              <textarea name="content" id="content"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>
          </div>

          <div class="flex items-center justify-between mt-4">
            <div class="flex space-x-4">
              <button type="button" onclick="previewNewsletter()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Предпросмотр
              </button>
              <button type="button" id="save-draft" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Сохранить черновик
              </button>
            </div>
            <div class="flex space-x-4">
              <a href="/admin/newsletters" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Отмена
              </a>
              <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Создать рассылку
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
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

<script>
  function updateProgress() {
    const subject = document.getElementById('subject').value;
    let content = '';

    // Check if editor is initialized
    if (tinymce.get('content')) {
      content = tinymce.get('content').getContent();
    }

    let progress = 0;
    if (subject) progress += 50;
    if (content) progress += 50;

    document.getElementById('progress-bar').style.width = `${progress}%`;
    document.getElementById('progress-text').textContent = `${progress}%`;
  }

  // Add form submission handling
  document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const content = tinymce.get('content').getContent();
    if (!content) {
      alert('Пожалуйста, заполните содержание письма');
      return;
    }
    this.submit();
  });

  function previewNewsletter() {
    const content = tinymce.get('content').getContent();
    const subject = document.getElementById('subject').value;
    const previewModal = document.getElementById('preview-modal');
    const previewContent = document.getElementById('preview-content');

    previewContent.innerHTML = `
    <div class="max-w-2xl mx-auto">
      <h1 class="text-2xl font-bold text-indigo-600 mb-4">${subject || 'Без темы'}</h1>
      <div class="prose prose-indigo">${content || 'Пустое содержание'}</div>
    </div>
  `;

    previewModal.classList.remove('hidden');
  }

  function closePreviewModal() {
    document.getElementById('preview-modal').classList.add('hidden');
  }

  // Сохранение черновика
  document.getElementById('save-draft').addEventListener('click', function() {
    const subject = document.getElementById('subject').value;
    const content = tinymce.get('content').getContent();
    localStorage.setItem('newsletter_draft', JSON.stringify({
      subject,
      content
    }));
    alert('Черновик сохранен');
  });

  // Загрузка черновика при загрузке страницы
  window.addEventListener('load', function() {
    const draft = localStorage.getItem('newsletter_draft');
    if (draft) {
      const {
        subject,
        content
      } = JSON.parse(draft);
      document.getElementById('subject').value = subject;
      tinymce.get('content').setContent(content);
    }
  });

  // Обновление прогресса при изменении полей
  document.getElementById('subject').addEventListener('input', updateProgress);
  tinymce.get('content').on('input', updateProgress);
</script>
<a href="/admin/newsletters" class="bg-gray-200 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-300">Отмена</a>
<button type="submit" class="bg-indigo-600 px-4 py-2 rounded-md text-white hover:bg-indigo-500">Создать</button>
</div>
</div>
</div>