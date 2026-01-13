<?php
session_start();
include "connection.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Daily Journal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document. documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
  </script>
</head>
<body class="bg-gradient-to-b from-pink-50 to-blue-100 dark:from-gray-900 dark:to-gray-800 min-h-screen text-gray-800 dark:text-gray-100 pt-20 transition-colors duration-300">
<?php include "navbar.php"; ?>

<section id="dashboard" class="pb-12 px-2 sm:px-8 max-w-4xl mx-auto">
  <div class="bg-gradient-to-tr from-indigo-100 to-white dark:from-gray-800 dark:to-gray-700 rounded-2xl shadow-lg p-8 flex flex-col md:flex-row items-center gap-8 mb-10">
    <div class="flex-1">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-700 dark:text-indigo-300 mb-3">Selamat Datang di My Daily Journal</h1>
      <p class="text-gray-700 dark:text-gray-300 text-lg mb-5">Catat aktivitas harianmu dan kelola artikel/galleries. </p>
      <div class="flex gap-4">
        <a href="#article" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">Mulai Menulis</a>
        <a href="#gallery" class="px-6 py-2 rounded-lg bg-white dark:bg-gray-600 border border-indigo-600 dark:border-indigo-400 text-indigo-700 dark:text-indigo-200 font-semibold hover:bg-indigo-50 dark:hover:bg-gray-500 transition">Lihat Gallery</a>
      </div>
    </div>
    <div class="flex-1 flex justify-center">
      <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2" alt="Dashboard Illustration" class="rounded-2xl shadow-lg w-72 h-56 object-cover" />
    </div>
  </div>
  
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
    <?php
    $jmlArticle = $conn->query("SELECT COUNT(*) as c FROM article")->fetch_assoc()['c'];
    $jmlGallery = $conn->query("SELECT COUNT(*) as c FROM gallery")->fetch_assoc()['c'];
    ?>
    <div class="bg-white dark:bg-gray-700 rounded-xl shadow p-5 flex flex-col items-center">
      <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-300"><?= $jmlArticle ?></div>
      <div class="text-gray-700 dark:text-gray-300">Artikel</div>
    </div>
    <div class="bg-white dark:bg-gray-700 rounded-xl shadow p-5 flex flex-col items-center">
      <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-300"><?= $jmlGallery ?></div>
      <div class="text-gray-700 dark:text-gray-300">Gallery</div>
    </div>
    <div class="bg-white dark:bg-gray-700 rounded-xl shadow p-5 flex flex-col items-center">
      <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-300">3</div>
      <div class="text-gray-700 dark:text-gray-300">Aktivitas</div>
    </div>
  </div>
</section>

<section id="article" class="text-center py-10">
  <div class="container mx-auto px-2">
    <h1 class="font-bold text-3xl text-pink-700 dark:text-pink-400 mb-8">Article</h1>
    <div class="grid grid-cols-1 md: grid-cols-3 gap-8 justify-center">
      <?php
      $hasil = $conn->query("SELECT * FROM article ORDER BY tanggal DESC");
      while ($row = $hasil->fetch_assoc()) {
      ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg h-full flex flex-col">
          <?php if (! empty($row["gambar"]) && file_exists('img/' . $row["gambar"])): ?>
            <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" class="rounded-t-2xl h-40 w-full object-cover" alt="<?= htmlspecialchars($row["judul"]) ?>" />
          <?php else: ?>
            <div class="bg-gradient-to-br from-pink-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 h-40 w-full flex items-center justify-center text-gray-300 text-5xl rounded-t-2xl">? </div>
          <?php endif; ?>
          <div class="p-4 flex-1 flex flex-col">
            <h5 class="font-bold text-lg text-pink-700 dark:text-pink-300 mb-2"><?= htmlspecialchars($row["judul"]) ?></h5>
            <p class="text-gray-700 dark:text-gray-300 mb-4 flex-1"><?= htmlspecialchars($row["isi"]) ?></p>
          </div>
          <div class="px-4 pb-4 text-right text-xs text-gray-400"><?= htmlspecialchars($row["tanggal"]) ?></div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<section id="gallery" class="py-12">
  <div class="container mx-auto px-2">
    <h1 class="font-bold text-3xl text-blue-700 dark:text-blue-400 mb-8 text-center">Gallery</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-center">
      <?php
      $hasil_gallery = $conn->query("SELECT * FROM gallery ORDER BY tanggal DESC");
      if ($hasil_gallery && $hasil_gallery->num_rows > 0) {
        while ($g = $hasil_gallery->fetch_assoc()) {
      ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg flex flex-col overflow-hidden hover:shadow-2xl transition-shadow">
          <img src="img/<?= htmlspecialchars($g["file"]) ?>" class="h-48 w-full object-cover rounded-t-2xl" alt="<?= htmlspecialchars($g["judul"]) ?>" />
          <div class="p-4 flex-1 flex flex-col">
            <h5 class="font-bold text-lg text-indigo-700 dark:text-indigo-300 mb-2"><?= htmlspecialchars($g["judul"]) ?></h5>
            <p class="text-gray-600 dark:text-gray-400 mb-3 flex-1"><?= htmlspecialchars($g["deskripsi"]) ?></p>
            <div class="text-xs text-gray-400 mt-auto"><?= htmlspecialchars($g["tanggal"]) ?></div>
          </div>
        </div>
        <?php }} else { echo '<div class="col-span-3 text-center text-gray-400">Belum ada foto di gallery. </div>'; } ?>
    </div>
  </div>
</section>

<footer class="mt-16 bg-white dark:bg-gray-800 border-t border-pink-100 dark:border-gray-700 py-8">
  <div class="container mx-auto max-w-2xl flex flex-col items-center gap-6">
    <div class="flex items-center gap-4">
      <img src="https://ui-avatars.com/api/? name=Rizki&background=6d28d9&color=fff&size=64" alt="Avatar" class="w-16 h-16 rounded-full shadow-lg border-4 border-indigo-200 dark:border-indigo-600"/>
      <div>
        <div class="font-bold text-lg text-indigo-700 dark:text-indigo-300">Rizki Ardiansyah Novianto</div>
        <div class="text-gray-600 dark:text-gray-400 text-sm">NIM: <span class="font-semibold">A11.2024.15546</span></div>
        <div class="text-xs mt-1 px-2 py-1 inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full">Informatika, Universitas Dian Nuswantoro</div>
      </div>
    </div>
    <div class="text-xs text-gray-400">&copy; <?= date("Y") ?> My Daily Journal &ndash; All Rights Reserved</div>
  </div>
</footer>
</body>
</html>