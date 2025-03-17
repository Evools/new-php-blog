<?php

require_once "./Controller/DatabaseController.php";
require_once "./Controller/CategoriesController.php";

$db = DatabaseController::getInstance();
$conn = $db->getConnect();

$all_categories = new CategoriesController($conn);
$categories = $all_categories->getCategories();




?>


<div class="relative overflow-x-auto shadow-lg sm:rounded-lg bg-white p-4">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 px-2 py-2 text-sm border border-gray-200 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" class="border-none bg-transparent w-full outline-none" placeholder="Поиск...">
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
            <tr>
                <th scope="col" class="px-6 py-4 font-medium">ID</th>
                <th scope="col" class="px-6 py-4 font-medium">Название</th>
                <th scope="col" class="px-6 py-4 font-medium">Дата создания</th>
                <th scope="col" class="px-6 py-4 font-medium text-right">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr class="bg-white border-b last:border-b-0 hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900"><?= $category['id'] ?></td>
                    <td class="px-6 py-4"><?= $category['name'] ?></td>
                    <td class="px-6 py-4"><?= $category['created_at'] ?></td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Изменить
                        </button>
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Удалить
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="flex items-center justify-between pt-4">
        <div class="text-sm text-gray-700">
            Показано <span class="font-medium">1-10</span> из <span class="font-medium">20</span>
        </div>
        <div class="flex space-x-2">
            <button class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Назад
            </button>
            <button class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-lg hover:bg-blue-700">
                Вперед
            </button>
        </div>
    </div>
</div>