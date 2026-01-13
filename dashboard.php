<?php
$jmlArticle = $conn->query("SELECT COUNT(*) as c FROM article")->fetch_assoc()['c'];
$jmlGallery = $conn->query("SELECT COUNT(*) as c FROM gallery")->fetch_assoc()['c'];
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex flex-col justify-between border border-pink-100 dark:border-gray-700">
    <div class="flex items-center gap-3 mb-3">
      <span class="inline-block w-5 h-5 bg-pink-600 rounded mr-2"></span>
      <span class="font-bold text-xl text-pink-600 dark:text-pink-400">Article</span>
    </div>
    <div class="flex items-center justify-between">
      <span class="rounded-full bg-pink-500 text-white px-5 py-2 text-2xl font-bold"><?= $jmlArticle ?></span>
      <a href="admin.php?page=article" class="ml-4 bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300 px-4 py-2 rounded-lg hover:bg-pink-200 dark:hover:bg-pink-800 font-semibold shadow transition">Lihat Semua</a>
    </div>
  </div>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex flex-col justify-between border border-pink-100 dark:border-gray-700">
    <div class="flex items-center gap-3 mb-3">
      <span class="inline-block w-5 h-5 bg-blue-700 rounded-full mr-2"></span>
      <span class="font-bold text-xl text-blue-700 dark:text-blue-400">Gallery</span>
    </div>
    <div class="flex items-center justify-between">
      <span class="rounded-full bg-blue-500 text-white px-5 py-2 text-2xl font-bold"><?= $jmlGallery ?></span>
      <a href="admin.php?page=gallery" class="ml-4 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 font-semibold shadow transition">Lihat Semua</a>
    </div>
  </div>
</div>
```