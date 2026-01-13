<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include "connection.php";
if (!isset($_SESSION['username'])) { header("location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Daily Journal | Admin</title>
  <link rel="icon" href="img/logo.png" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
  </script>
</head>
<body class="bg-gradient-to-tr from-pink-100 to-blue-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex flex-col text-gray-800 dark:text-gray-100 pt-20 transition-colors duration-300">
<?php include "navbar.php"; ?>
<section id="content" class="flex-1 p-5">
  <div class="max-w-5xl mx-auto">
    <?php
    $allow = ['dashboard', 'article', 'gallery'];
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    $limit = 3;
    $hal = isset($_GET['hal']) ? intval($_GET['hal']) : 1;
    if ($hal < 1) $hal = 1;
    $limit_start = ($hal - 1) * $limit;
    if (in_array($page, $allow)) {
      if ($page == 'gallery') {
        echo '<h4 class="text-xl font-semibold mb-4 border-b border-blue-100 dark:border-gray-700 pb-2">Gallery <span class="text-sm font-normal">(Admin - Tabel)</span></h4>';
        $sql = "SELECT * FROM gallery ORDER BY tanggal DESC LIMIT $limit_start, $limit";
        $hasil = $conn->query($sql);
        $totalData = $conn->query("SELECT COUNT(*) as total FROM gallery")->fetch_assoc()['total'];
        $jumlah_page = ceil($totalData / $limit);
        echo '<div class="overflow-x-auto mt-6"><table class="min-w-full bg-white dark:bg-gray-800 border border-blue-200 dark:border-gray-700 rounded-xl shadow-xl text-sm"><thead class="bg-blue-100 dark:bg-gray-700"><tr><th class="py-2 px-3 font-bold text-blue-700 dark:text-blue-300">No</th><th class="py-2 px-3 font-bold">Judul</th><th class="py-2 px-3 font-bold">Deskripsi</th><th class="py-2 px-3 font-bold">Gambar</th><th class="py-2 px-3 font-bold">Tanggal</th><th class="py-2 px-3 font-bold">Aksi</th></tr></thead><tbody>';
        $no = $limit_start + 1;
        if ($hasil && $hasil->num_rows > 0) { while ($g = $hasil->fetch_assoc()) { echo '<tr class="odd:bg-blue-50 even:bg-pink-50 dark:odd:bg-gray-700 dark:even:bg-gray-800 transition"><td class="py-1 px-3">'.$no++.'</td><td class="py-1 px-3">'. htmlspecialchars($g["judul"]).'</td><td class="py-1 px-3">'.htmlspecialchars($g["deskripsi"]).'</td><td class="py-1 px-3">'; if (!empty($g["file"]) && file_exists('img/' . $g["file"])) { echo '<img src="img/'.htmlspecialchars($g["file"]).'" class="w-16 h-16 rounded shadow ring-2 ring-blue-200 dark:ring-blue-700 object-cover" alt="">'; } else { echo '<span class="text-xs text-gray-400 italic">No Image</span>'; } echo '</td><td class="py-1 px-3">'. htmlspecialchars($g["tanggal"]).'</td><td class="py-1 px-3"><div class="flex gap-2"><button class="btn-delete-gallery bg-pink-600 hover:bg-pink-800 text-white rounded p-1.5" data-id="'.$g['id'].'" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div></td></tr>'; } } else { echo '<tr><td colspan="6" class="text-center text-gray-400 py-4">Belum ada gallery.</td></tr>'; }
        echo '</tbody></table></div>';
        // PAGINATION GALLERY
        if ($jumlah_page > 1) {
          echo '<div class="flex justify-center mt-4 gap-1">';
          for ($i = 1; $i <= $jumlah_page; $i++) {
            $active = ($i == $hal) ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-700 text-blue-700 dark:text-blue-300';
            echo '<a href="admin.php?page=gallery&hal='.$i.'" class="px-3 py-1 rounded border border-blue-200 dark:border-gray-700 '.$active.'">'.$i.'</a>';
          }
          echo '</div>';
        }
      } elseif ($page == 'article') {
        echo '<h4 class="text-xl font-semibold mb-4 border-b border-pink-100 dark:border-gray-700 pb-2">Article <span class="text-sm font-normal">(Admin - Tabel)</span></h4>';
        $sql = "SELECT * FROM article ORDER BY tanggal DESC LIMIT $limit_start, $limit";
        $hasil = $conn->query($sql);
        $totalData = $conn->query("SELECT COUNT(*) as total FROM article")->fetch_assoc()['total'];
        $jumlah_page = ceil($totalData / $limit);
        echo '<div class="overflow-x-auto mt-6"><table class="min-w-full bg-white dark:bg-gray-800 border border-pink-200 dark:border-gray-700 rounded-xl shadow-xl text-sm"><thead class="bg-pink-100 dark:bg-gray-700"><tr><th class="py-2 px-3 font-bold text-pink-700 dark:text-pink-300">No</th><th class="py-2 px-3 font-bold">Judul</th><th class="py-2 px-3 font-bold">Isi</th><th class="py-2 px-3 font-bold">Gambar</th><th class="py-2 px-3 font-bold">Tanggal</th><th class="py-2 px-3 font-bold">Aksi</th></tr></thead><tbody>';
        $no = $limit_start + 1;
        if ($hasil && $hasil->num_rows > 0) { while ($a = $hasil->fetch_assoc()) { echo '<tr class="odd:bg-blue-50 even:bg-pink-50 dark:odd:bg-gray-700 dark:even:bg-gray-800 transition"><td class="py-1 px-3">'.$no++.'</td><td class="py-1 px-3">'. htmlspecialchars($a["judul"]).'</td><td class="py-1 px-3">'.htmlspecialchars($a["isi"]).'</td><td class="py-1 px-3">'; if (!empty($a["gambar"]) && file_exists('img/' . $a["gambar"])) { echo '<img src="img/'.htmlspecialchars($a["gambar"]).'" class="w-16 h-16 rounded shadow ring-2 ring-pink-200 dark:ring-pink-700 object-cover" alt="">'; } else { echo '<span class="text-xs text-gray-400 italic">No Image</span>'; } echo '</td><td class="py-1 px-3">'.htmlspecialchars($a["tanggal"]).'</td><td class="py-1 px-3"><div class="flex gap-2"><button class="btn-delete-article bg-pink-600 hover:bg-pink-800 text-white rounded p-1.5" data-id="'.$a['id'].'" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div></td></tr>'; } } else { echo '<tr><td colspan="6" class="text-center text-gray-400 py-4">Belum ada artikel.</td></tr>'; }
        echo '</tbody></table></div>';
        // PAGINATION ARTICLE
        if ($jumlah_page > 1) {
          echo '<div class="flex justify-center mt-4 gap-1">';
          for ($i = 1; $i <= $jumlah_page; $i++) {
            $active = ($i == $hal) ? 'bg-pink-600 text-white' : 'bg-white dark:bg-gray-700 text-pink-700 dark:text-pink-300';
            echo '<a href="admin.php?page=article&hal='.$i.'" class="px-3 py-1 rounded border border-pink-200 dark:border-gray-700 '.$active.'">'.$i.'</a>';
          }
          echo '</div>';
        }
      } else {
        echo '<h4 class="text-xl font-semibold mb-4 border-b border-pink-100 dark:border-gray-700 pb-2">Dashboard</h4>';
        include("dashboard.php");
      }
    } else { echo "<div class='bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded p-3 text-center'>Halaman tidak ditemukan.</div>"; }
    ?>
  </div>
</section>
<script>
$(document).ready(function(){
  $(document).on('click', '.btn-delete-article', function(){ var id = $(this).data('id'); if(confirm('Yakin hapus artikel?')){ $.post('article_action.php', { aksi: 'delete', id: id }, function(resp){ if(resp.trim() === 'OK'){ location.reload(); } else { alert('Hapus gagal:  ' + resp); } }); } });
  $(document).on('click', '.btn-delete-gallery', function(){ var id = $(this).data('id'); if(confirm('Yakin hapus gallery?')){ $.post('gallery_action.php', { aksi: 'delete', id: id }, function(resp){ if(resp.trim() === 'OK'){ location.reload(); } else { alert('Hapus gagal:  ' + resp); } }); } });
});
</script>
</body>
</html>
```