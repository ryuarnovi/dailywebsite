<?php
// Query jumlah artikel & gallery
$sql1 = "SELECT COUNT(*) as jml FROM article";
$sql2 = "SELECT COUNT(*) as jml FROM gallery";
$jumlah_article = ($conn->query($sql1)->fetch_assoc())['jml'] ?? 0;
$jumlah_gallery = ($conn->query($sql2)->fetch_assoc())['jml'] ?? 0;
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
  <div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between border border-pink-100">
    <div class="flex items-center gap-3 mb-3">
      <span class="inline-block w-5 h-5 bg-pink-600 rounded mr-2"></span>
      <span class="font-bold text-xl text-pink-600">Article</span>
    </div>
    <div class="flex items-center justify-between">
      <span class="rounded-full bg-pink-500 text-white px-5 py-2 text-2xl font-bold"><?= $jumlah_article ?></span>
      <a href="admin.php?page=article" class="ml-4 bg-pink-100 text-pink-700 px-4 py-2 rounded-lg hover:bg-pink-200 font-semibold shadow transition">Lihat Semua</a>
    </div>
  </div>
  <div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between border border-pink-100">
    <div class="flex items-center gap-3 mb-3">
      <span class="inline-block w-5 h-5 bg-blue-700 rounded-full mr-2"></span>
      <span class="font-bold text-xl text-blue-700">Gallery</span>
    </div>
    <div class="flex items-center justify-between">
      <span class="rounded-full bg-blue-500 text-white px-5 py-2 text-2xl font-bold"><?= $jumlah_gallery ?></span>
      <a href="admin.php?page=gallery" class="ml-4 bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 font-semibold shadow transition">Lihat Semua</a>
    </div>
  </div>
</div>