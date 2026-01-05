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
</head>
<body class="bg-gradient-to-b from-[#f5e5eb] to-[#e5edf5] min-h-screen text-gray-800">
  <?php include "navbar.php"; ?>

  <section id="dashboard" class="pt-24 pb-12 px-2 sm:px-8 max-w-4xl mx-auto">
    <div class="bg-gradient-to-tr from-indigo-100 to-white rounded-2xl shadow-lg p-8 flex flex-col md:flex-row items-center gap-8 mb-10">
      <div class="flex-1">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-700 mb-3">Selamat Datang di My Daily Journal</h1>
        <p class="text-gray-700 text-lg mb-5">Catat aktivitas harianmu dan kelola artikel/galleries.</p>
        <div class="flex gap-4">
          <a href="#article" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Mulai Menulis</a>
          <a href="#gallery" class="px-6 py-2 rounded-lg bg-white border border-indigo-600 text-indigo-700 font-semibold hover:bg-indigo-50">Lihat Gallery</a>
        </div>
      </div>
      <div class="flex-1 flex justify-center">
        <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2" alt="Dashboard Illustration" class="rounded-2xl shadow-lg w-72 h-56 object-cover" />
      </div>
    </div>
    <!-- Quick Stats Manual -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      <div class="bg-white/80 rounded-xl shadow p-5 flex flex-col items-center">
        <div class="text-3xl font-bold text-indigo-600">12</div>
        <div class="text-gray-700">Artikel</div>
      </div>
      <div class="bg-white/80 rounded-xl shadow p-5 flex flex-col items-center">
        <div class="text-3xl font-bold text-indigo-600">5</div>
        <div class="text-gray-700">Gallery</div>
      </div>
      <div class="bg-white/80 rounded-xl shadow p-5 flex flex-col items-center">
        <div class="text-3xl font-bold text-indigo-600">3</div>
        <div class="text-gray-700">Aktivitas</div>
      </div>
    </div>
  </section>
  <!-- ARTICLE SECTION from Database -->
  <section id="article" class="text-center py-10">
    <div class="container mx-auto px-2">
      <h1 class="font-bold text-3xl text-pink-700 mb-8">Article</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-center">
        <?php
        $sql = "SELECT * FROM article ORDER BY tanggal DESC";
        $hasil = $conn->query($sql);
        while ($row = $hasil->fetch_assoc()) {
        ?>
          <div class="bg-white rounded-2xl shadow-lg h-full flex flex-col">
            <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" class="rounded-t-2xl h-40 w-full object-cover" alt="<?= htmlspecialchars($row["judul"]) ?>" />
            <div class="p-4 flex-1 flex flex-col">
              <h5 class="font-bold text-lg text-pink-700 mb-2"><?= htmlspecialchars($row["judul"]) ?></h5>
              <p class="text-gray-700 mb-4 flex-1"><?= htmlspecialchars($row["isi"]) ?></p>
            </div>
            <div class="px-4 pb-4 text-right text-xs text-gray-400">
              <?= htmlspecialchars($row["tanggal"]) ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <!-- GALLERY SECTION -->
<section id="gallery" class="py-12">
  <div class="container mx-auto px-2">
    <h1 class="font-bold text-3xl text-blue-700 mb-8 text-center">Gallery</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-center">
      <?php
      $sql_gallery = "SELECT * FROM gallery ORDER BY tanggal DESC";
      $hasil_gallery = $conn->query($sql_gallery);
      if ($hasil_gallery && $hasil_gallery->num_rows > 0) {
        while ($g = $hasil_gallery->fetch_assoc()) {
      ?>
        <div class="bg-white rounded-2xl shadow-lg flex flex-col overflow-hidden hover:shadow-2xl transition-shadow">
          <img src="img/<?= htmlspecialchars($g["file"]) ?>" class="h-48 w-full object-cover rounded-t-2xl" alt="<?= htmlspecialchars($g["judul"]) ?>" />
          <div class="p-4 flex-1 flex flex-col">
            <h5 class="font-bold text-lg text-indigo-700 mb-2"><?= htmlspecialchars($g["judul"]) ?></h5>
            <p class="text-gray-600 mb-3 flex-1"><?= htmlspecialchars($g["deskripsi"]) ?></p>
            <div class="text-xs text-gray-400 mt-auto"><?= htmlspecialchars($g["tanggal"]) ?></div>
          </div>
        </div>
      <?php
        }
      } else {
        echo '<div class="col-span-3 text-center text-gray-400">Belum ada foto di gallery.</div>';
      }
      ?>
    </div>
  </div>
</section>
  <!-- PROFILE SECTION -->
<footer class="mt-16 bg-white/70 border-t border-pink-100 py-8">
  <div class="container mx-auto max-w-2xl flex flex-col items-center gap-6">
    <div class="flex items-center gap-4">
      <!-- Avatar pakai ui-avatars -->
      <img src="images/profile.jpg"
           alt="Avatar Rizki" class="w-16 h-16 rounded-full shadow-lg border-4 border-indigo-200"/>
      <div>
        <div class="font-bold text-lg text-indigo-700">Rizki Ardiansyah Novianto</div>
        <div class="text-gray-600 text-sm">NIM: <span class="font-semibold">A11.2024.15546</span></div>
        <div class="text-xs mt-1 px-2 py-1 inline-block bg-indigo-100 text-indigo-700 rounded-full">
          Informatika, Universitas Dian Nuswantoro
        </div>
      </div>
    </div>
    <div class="text-xs text-gray-400 mt-1">
      &copy; <?= date("Y") ?> My Daily Journal &ndash; All Rights Reserved
    </div>
  </div>
</footer>
</body>
</html>