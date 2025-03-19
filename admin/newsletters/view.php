<?php
require_once "./Controller/DatabaseController.php";
require_once "./Controller/NewsletterController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

$newsletter = new NewsletterController($conn);
$newsletters = $newsletter->getAllNewsletters();

$titleName = "Управление рассылками";
include "./layout/head.php";
?>
<main>
  <?php include "./admin/layout/sidebar.php"; ?>
  <div class="p-4 sm:ml-64 mt-16">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Управление рассылками</h1>

      <a href="/admin/newsletters/create" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        Создать рассылку
      </a>
    </div>

    <div class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Тема</th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Статус</th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Дата создания</th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Дата отправки</th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <?php foreach ($newsletters as $item): ?>
              <tr>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <div class="text-sm leading-5 font-medium text-gray-900"><?php echo htmlspecialchars($item['subject']); ?></div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full <?php echo $item['status'] === 'sent' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <?php if ($item['status'] === 'sent'): ?>
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      <?php else: ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                      <?php endif; ?>
                    </svg>
                    <?php echo $item['status'] === 'sent' ? 'Отправлено' : 'Черновик'; ?>
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                  <?php echo date('d.m.Y H:i', strtotime($item['created_at'])); ?>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                  <?php echo $item['sent_at'] ? date('d.m.Y H:i', strtotime($item['sent_at'])) : '-'; ?>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                  <div class="flex justify-end space-x-3">
                    <?php if ($item['status'] === 'draft'): ?>
                      <form action="/admin/newsletters/send/<?php echo $item['id']; ?>" method="POST" class="inline">
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-md transition-colors duration-200">
                          <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                          </svg>
                          Отправить
                        </button>
                      </form>
                      <a href="/admin/newsletters/edit/<?php echo $item['id']; ?>" onclick="event.preventDefault(); window.location.href='/admin/newsletters/edit/<?php echo $item['id']; ?>'" class="inline-flex items-center px-3 py-1.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-md transition-colors duration-200">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Редактировать
                      </a>
                    <?php endif; ?>
                    <button type="button" onclick="showDeleteModal('<?php echo $item['id']; ?>', '<?php echo htmlspecialchars($item['subject'], ENT_QUOTES); ?>')" class="inline-flex items-center px-3 py-1.5 border border-red-600 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-colors duration-200">
                      <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Удалить
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>

  <!-- Delete Newsletter Modal -->
  <div id="deleteNewsletterModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3 text-center">
        <svg class="mx-auto mb-4 w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900">Удалить рассылку?</h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-gray-500">Вы действительно хотите удалить рассылку "<span id="deleteNewsletterSubject"></span>"?</p>
        </div>
        <div class="flex justify-center gap-3 mt-3">
          <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
            Отмена
          </button>
          <form id="deleteNewsletterForm" method="POST" action="/admin/newsletters/delete">
            <input type="hidden" name="delete_newsletter" id="deleteNewsletterId">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
              Удалить
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showDeleteModal(id, subject) {
      document.getElementById('deleteNewsletterId').value = id;
      document.getElementById('deleteNewsletterSubject').textContent = subject;
      document.getElementById('deleteNewsletterModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
      document.getElementById('deleteNewsletterModal').classList.add('hidden');
    }
  </script>
</main>