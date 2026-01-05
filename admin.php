<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include "connection.php";
if (!isset($_SESSION['username'])) {
  header("location:login.php");
  exit();
}
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
</head>
<body class="bg-gradient-to-tr from-pink-100 to-blue-100 min-h-screen flex flex-col">
<?php include "navbar.php"; ?>
<section id="content" class="flex-1 p-5">
  <div class="max-w-5xl mx-auto">
    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $allow = ['dashboard', 'article', 'gallery'];
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

    $limit = 3;
    $hal = isset($_GET['hal']) ? intval($_GET['hal']) : 1;
    if ($hal < 1) $hal = 1;
    $limit_start = ($hal - 1) * $limit;

    if (in_array($page, $allow)) {
      if ($page == 'gallery') {
        echo '<h4 class="text-xl font-semibold mb-4 border-b border-blue-100">Gallery <span class="text-sm font-normal">(Admin - Tabel)</span></h4>';
        $sql = "SELECT * FROM gallery ORDER BY tanggal DESC LIMIT $limit_start, $limit";
        $hasil = $conn->query($sql);
        $sqlAll = "SELECT COUNT(*) as total FROM gallery";
        $totalRes = $conn->query($sqlAll);
        $totalData = $totalRes ? ($totalRes->fetch_assoc())['total'] : 0;
        $jumlah_page = ceil($totalData / $limit);

        echo '<div class="overflow-x-auto mt-6">';
        echo '<table class="min-w-full bg-white border border-blue-200 rounded-xl shadow-xl text-sm">';
        echo '<thead class="bg-blue-100"><tr>
                <th class="py-2 px-3 font-bold text-blue-700">No</th>
                <th class="py-2 px-3 font-bold">Judul</th>
                <th class="py-2 px-3 font-bold">Deskripsi</th>
                <th class="py-2 px-3 font-bold">Gambar</th>
                <th class="py-2 px-3 font-bold">Tanggal</th>
                <th class="py-2 px-3 font-bold">Aksi</th>
              </tr></thead><tbody>';

        $no = $limit_start + 1;
        if ($hasil && $hasil->num_rows > 0) {
          while ($g = $hasil->fetch_assoc()) {
            echo '<tr class="odd:bg-blue-50 even:bg-pink-50 transition">';
            echo '<td class="py-1 px-3">'.$no++.'</td>';
            echo '<td class="py-1 px-3">'.htmlspecialchars($g["judul"]).'</td>';
            echo '<td class="py-1 px-3">'.htmlspecialchars($g["deskripsi"]).'</td>';
            echo '<td class="py-1 px-3">';
            if (!empty($g["file"]) && file_exists('img/' . $g["file"])) {
              echo '<img src="img/'.htmlspecialchars($g["file"]).'" class="w-16 h-16 rounded shadow ring-2 ring-blue-200 object-cover" alt="">';
            } else {
              echo '<span class="text-xs text-gray-400 italic">No Image</span>';
            }
            echo '</td>';
            echo '<td class="py-1 px-3">'.htmlspecialchars($g["tanggal"]).'</td>';
            echo '<td class="py-1 px-3">
              <div class="flex gap-2">
                <button class="btn-edit-gallery bg-emerald-500 hover:bg-emerald-700 text-white rounded p-1.5"
                  data-id="'.$g['id'].'"
                  data-judul="'.htmlspecialchars($g['judul']).'"
                  data-deskripsi="'.htmlspecialchars($g['deskripsi']).'"
                  data-file="'.htmlspecialchars($g['file']).'"
                  title="Edit">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l9.188-9.188a2 2 0 00-2.828-2.828l-9.188 9.188A1 1 0 004.586 20H4z"/>
                  </svg>
                </button>
                <button class="btn-delete-gallery bg-pink-600 hover:bg-pink-800 text-white rounded p-1.5"
                  data-id="'.$g['id'].'" title="Hapus">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2M3 7h18"/>
                  </svg>
                </button>
              </div>
            </td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="6" class="text-center text-gray-400 py-4">Belum ada gallery.</td></tr>';
        }
        echo '</tbody></table></div>';
        // pagination
        if ($jumlah_page > 1) {
          echo '<div class="flex justify-end mt-4">
            <nav><ul class="inline-flex -space-x-px">';
          if ($hal > 1) {
            echo '<li><a href="admin.php?page=gallery&hal=1" class="px-3 py-1 mx-0.5 bg-blue-100 hover:bg-blue-200 rounded-l text-blue-700">First</a></li>';
            echo '<li><a href="admin.php?page=gallery&hal='.($hal-1).'" class="px-3 py-1 mx-0.5 bg-blue-100 hover:bg-blue-200 text-blue-700">&laquo;</a></li>';
          } else {
            echo '<li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400 rounded-l">First</span></li>
              <li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400">&laquo;</span></li>';
          }
          for ($i = 1; $i <= $jumlah_page; $i++) {
            $active = ($hal == $i) ? ' bg-blue-600 text-white font-bold' : ' bg-white text-blue-700';
            echo '<li><a href="admin.php?page=gallery&hal='.$i.'" class="px-3 py-1 mx-0.5 rounded'.$active.'">'.$i.'</a></li>';
          }
          if ($hal < $jumlah_page) {
            echo '<li><a href="admin.php?page=gallery&hal='.($hal+1).'" class="px-3 py-1 mx-0.5 bg-blue-100 hover:bg-blue-200 text-blue-700">&raquo;</a></li>';
            echo '<li><a href="admin.php?page=gallery&hal='.$jumlah_page.'" class="px-3 py-1 mx-0.5 bg-blue-100 hover:bg-blue-200 rounded-r text-blue-700">Last</a></li>';
          } else {
            echo '<li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400">&raquo;</span></li>
              <li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400 rounded-r">Last</span></li>';
          }
          echo '</ul></nav></div>';
        }
      } elseif ($page == 'article') {
        echo '<h4 class="text-xl font-semibold mb-4 border-b border-pink-100">Article <span class="text-sm font-normal">(Admin - Tabel)</span></h4>';
        $sql = "SELECT * FROM article ORDER BY tanggal DESC LIMIT $limit_start, $limit";
        $hasil = $conn->query($sql);
        $sqlAll = "SELECT COUNT(*) as total FROM article";
        $totalRes = $conn->query($sqlAll);
        $totalData = $totalRes ? ($totalRes->fetch_assoc())['total'] : 0;
        $jumlah_page = ceil($totalData / $limit);

        echo '<div class="overflow-x-auto mt-6">';
        echo '<table class="min-w-full bg-white border border-pink-200 rounded-xl shadow-xl text-sm">';
        echo '<thead class="bg-pink-100"><tr>
                <th class="py-2 px-3 font-bold text-pink-700">No</th>
                <th class="py-2 px-3 font-bold">Judul</th>
                <th class="py-2 px-3 font-bold">Isi</th>
                <th class="py-2 px-3 font-bold">Gambar</th>
                <th class="py-2 px-3 font-bold">Tanggal</th>
                <th class="py-2 px-3 font-bold">Aksi</th>
              </tr></thead><tbody>';
        $no = $limit_start + 1;
        if ($hasil && $hasil->num_rows > 0) {
          while ($a = $hasil->fetch_assoc()) {
            echo '<tr class="odd:bg-blue-50 even:bg-pink-50 transition">';
            echo '<td class="py-1 px-3">'.$no++.'</td>';
            echo '<td class="py-1 px-3">'.htmlspecialchars($a["judul"]).'</td>';
            echo '<td class="py-1 px-3">'.htmlspecialchars($a["isi"]).'</td>';
            echo '<td class="py-1 px-3">';
            if (!empty($a["gambar"]) && file_exists('img/' . $a["gambar"])) {
              echo '<img src="img/'.htmlspecialchars($a["gambar"]).'" class="w-16 h-16 rounded shadow ring-2 ring-pink-200 object-cover" alt="">';
            } else {
              echo '<span class="text-xs text-gray-400 italic">No Image</span>';
            }
            echo '</td>';
            echo '<td class="py-1 px-3">'.htmlspecialchars($a["tanggal"]).'</td>';
            echo '<td class="py-1 px-3">
              <div class="flex gap-2">
                <button class="btn-edit-article bg-emerald-500 hover:bg-emerald-700 text-white rounded p-1.5"
                  data-id="'.$a['id'].'"
                  data-judul="'.htmlspecialchars($a['judul']).'"
                  data-isi="'.htmlspecialchars($a['isi']).'"
                  data-gambar="'.htmlspecialchars($a['gambar']).'"
                  title="Edit">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l9.188-9.188a2 2 0 00-2.828-2.828l-9.188 9.188A1 1 0 004.586 20H4z"/>
                  </svg>
                </button>
                <button class="btn-delete-article bg-pink-600 hover:bg-pink-800 text-white rounded p-1.5"
                  data-id="'.$a['id'].'" title="Hapus">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2M3 7h18"/>
                  </svg>
                </button>
              </div>
            </td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="6" class="text-center text-gray-400 py-4">Belum ada artikel.</td></tr>';
        }
        echo '</tbody></table></div>';
        if ($jumlah_page > 1) {
          echo '<div class="flex justify-end mt-4">
            <nav><ul class="inline-flex -space-x-px">';
          if ($hal > 1) {
            echo '<li><a href="admin.php?page=article&hal=1" class="px-3 py-1 mx-0.5 bg-pink-100 hover:bg-pink-200 rounded-l text-pink-700">First</a></li>';
            echo '<li><a href="admin.php?page=article&hal='.($hal-1).'" class="px-3 py-1 mx-0.5 bg-pink-100 hover:bg-pink-200 text-pink-700">&laquo;</a></li>';
          } else {
            echo '<li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400 rounded-l">First</span></li>
              <li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400">&laquo;</span></li>';
          }
          for ($i = 1; $i <= $jumlah_page; $i++) {
            $active = ($hal == $i) ? ' bg-pink-600 text-white font-bold' : ' bg-white text-pink-700';
            echo '<li><a href="admin.php?page=article&hal='.$i.'" class="px-3 py-1 mx-0.5 rounded'.$active.'">'.$i.'</a></li>';
          }
          if ($hal < $jumlah_page) {
            echo '<li><a href="admin.php?page=article&hal='.($hal+1).'" class="px-3 py-1 mx-0.5 bg-pink-100 hover:bg-pink-200 text-pink-700">&raquo;</a></li>';
            echo '<li><a href="admin.php?page=article&hal='.$jumlah_page.'" class="px-3 py-1 mx-0.5 bg-pink-100 hover:bg-pink-200 rounded-r text-pink-700">Last</a></li>';
          } else {
            echo '<li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400">&raquo;</span></li>
              <li><span class="px-3 py-1 mx-0.5 bg-gray-200 text-gray-400 rounded-r">Last</span></li>';
          }
          echo '</ul></nav></div>';
        }
      } else {
        echo '<h4 class="text-xl font-semibold mb-4 border-b border-pink-100">Dashboard</h4>';
        include("dashboard.php");
      }
    } else {
      echo "<div class='bg-red-100 text-red-700 rounded p-3 text-center'>Halaman tidak ditemukan.</div>";
    }
    ?>
  </div>
</section>
<script>
$(document).ready(function(){
  // Hapus Article
  $(document).on('click', '.btn-delete-article', function(){
    var id = $(this).data('id');
    if(confirm('Yakin ingin menghapus artikel id: ' + id + ' ?')) {
      $.post('article_action.php', { aksi: 'delete', id: id }, function(resp){
        if(resp.trim() === 'OK'){
          location.reload();
        } else {
          alert('Hapus gagal: ' + resp);
        }
      });
    }
  });
  // Hapus Gallery
  $(document).on('click', '.btn-delete-gallery', function(){
    var id = $(this).data('id');
    if(confirm('Yakin ingin menghapus gallery id: ' + id + ' ?')) {
      $.post('gallery_action.php', { aksi: 'delete', id: id }, function(resp){
        if(resp.trim() === 'OK'){
          location.reload();
        } else {
          alert('Hapus gagal: ' + resp);
        }
      });
    }
  });
});
</script>
</body>
</html>