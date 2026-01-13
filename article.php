<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include "connection.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Artikel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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
<body class="bg-gradient-to-b from-pink-50 to-blue-100 dark:from-gray-900 dark:to-gray-800 min-h-screen text-gray-800 dark:text-gray-100 pt-20 transition-colors duration-300">
<?php include "navbar.php"; ?>
<div class="container mx-auto px-3 py-6">
  <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-3xl font-bold text-pink-600 dark:text-pink-400">Article</h1>
    <button type="button" onclick="$('#modalTambah').removeClass('hidden')" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-lg shadow transition">+ Tambah Article</button>
  </div>
  <div id="article_data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php
    $res = $conn->query("SELECT * FROM article ORDER BY tanggal DESC");
    if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        echo '<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg flex flex-col overflow-hidden">';
        if (!empty($row["gambar"]) && file_exists('img/' . $row["gambar"])) {
          echo '<img src="img/'. htmlspecialchars($row["gambar"]).'" class="h-44 w-full object-cover rounded-t-2xl" alt="Gambar">';
        } else {
          echo '<div class="bg-gradient-to-br from-pink-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 h-44 w-full flex items-center justify-center text-gray-300 text-5xl rounded-t-2xl">?</div>';
        }
        echo '<div class="p-4 flex-1 flex flex-col">';
        echo '<h5 class="font-bold text-lg text-pink-700 dark:text-pink-300 mb-2">'.htmlspecialchars($row["judul"]).'</h5>';
        echo '<p class="text-gray-700 dark:text-gray-300 mb-3 flex-1">'.htmlspecialchars($row["isi"]).'</p>';
        echo '<div class="flex items-center justify-between mt-auto">';
        echo '<span class="text-xs text-gray-400">'.htmlspecialchars($row["tanggal"]).'</span>';
        echo '<div class="flex gap-2">';
        echo '<button type="button" class="btn-edit-article bg-emerald-500 hover:bg-emerald-700 text-white rounded p-1.5 transition" data-id="'.$row['id'].'" data-judul="'. htmlspecialchars($row['judul']).'" data-isi="'.htmlspecialchars($row['isi']).'" data-gambar="'. htmlspecialchars($row['gambar']).'" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>';
        echo '<button type="button" class="btn-delete-article bg-pink-600 hover:bg-pink-800 text-white rounded p-1.5 transition" data-id="'.$row['id'].'" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
        echo '</div></div></div></div>';
      }
    } else {
      echo '<div class="col-span-3 text-center text-gray-400">Belum ada artikel.</div>';
    }
    ?>
  </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
  <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full max-w-md relative shadow-lg">
    <button class="absolute top-2 right-3 text-gray-400 hover:text-red-500 text-2xl" onclick="$('#modalTambah').addClass('hidden')">&times;</button>
    <form id="formTambah" enctype="multipart/form-data" class="space-y-4">
      <h3 class="text-xl font-bold text-pink-600 dark: text-pink-400 mb-4">Tambah Artikel</h3>
      <div><label class="font-semibold text-pink-700 dark:text-pink-300">Judul</label><input type="text" name="judul" class="w-full mt-1 border border-pink-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2 focus:ring-2 focus:ring-pink-400" required></div>
      <div><label class="font-semibold text-pink-700 dark:text-pink-300">Isi</label><textarea name="isi" rows="3" class="w-full mt-1 border border-pink-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2 focus:ring-2 focus:ring-pink-400" required></textarea></div>
      <div><label class="font-semibold text-pink-700 dark: text-pink-300">Gambar</label><input type="file" name="gambar" accept="image/*" class="mt-1 border border-pink-200 dark: border-gray-600 dark: bg-gray-700 dark: text-white rounded px-3 py-2 w-full file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-pink-100 dark:file:bg-pink-900 file:text-pink-700 dark:file:text-pink-300"></div>
      <div class="text-right">
        <button type="button" onclick="$('#modalTambah').addClass('hidden')" class="bg-gray-200 dark:bg-gray-600 text-pink-600 dark:text-pink-300 px-4 py-2 rounded mr-2 hover:bg-gray-300 dark:hover:bg-gray-500">Batal</button>
        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-4 py-2 rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div id="modalEditArticle" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
  <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full max-w-md relative shadow-lg">
    <button class="absolute top-2 right-3 text-gray-400 hover:text-red-500 text-2xl" onclick="$('#modalEditArticle').addClass('hidden')">&times;</button>
    <form id="formEditArticle" enctype="multipart/form-data" class="space-y-4">
      <h3 class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mb-4">Edit Article</h3>
      <input type="hidden" name="id" id="edit_id">
      <div><label class="font-semibold text-pink-700 dark:text-pink-300">Judul</label><input type="text" name="judul" id="edit_judul" required class="w-full mt-1 border border-pink-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"></div>
      <div><label class="font-semibold text-pink-700 dark:text-pink-300">Isi</label><textarea name="isi" id="edit_isi" rows="3" required class="w-full mt-1 border border-pink-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"></textarea></div>
      <div><label class="font-semibold text-pink-700 dark:text-pink-300">Ganti Gambar</label><input type="file" name="gambar" id="edit_gambar" accept="image/*" class="w-full border border-pink-200 dark: border-gray-600 dark: bg-gray-700 dark: text-white rounded px-2 py-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-pink-100 dark:file:bg-pink-900 file:text-pink-700 dark:file:text-pink-300"><div id="old_gambar_wrap" class="mt-2"></div></div>
      <div class="text-right">
        <button type="button" onclick="$('#modalEditArticle').addClass('hidden')" class="bg-gray-100 dark:bg-gray-600 text-pink-600 dark:text-pink-300 px-4 py-2 rounded mr-2">Batal</button>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#formTambah').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('aksi', 'tambah');
    $. ajax({ url: 'article_action.php', type: 'POST', data: formData, contentType: false, processData: false, success: function(resp){ if(resp.trim()==='OK'){ $('#modalTambah').addClass('hidden'); $('#formTambah')[0].reset(); location.reload(); }else{ alert(resp); } }, error: function(){ alert('Gagal! '); } });
  });
  $(document).on('click', '.btn-edit-article', function() {
    $('#edit_id').val($(this).data('id'));
    $('#edit_judul').val($(this).data('judul'));
    $('#edit_isi').val($(this).data('isi'));
    var gambar = $(this).data('gambar');
    if(gambar){ $('#old_gambar_wrap').html('<img src="img/'+gambar+'" class="h-20 rounded shadow mb-1"/><div class="text-xs text-gray-500 dark:text-gray-400">Gambar sekarang</div>'); }else{ $('#old_gambar_wrap').html(''); }
    $('#modalEditArticle').removeClass('hidden');
  });
  $('#formEditArticle').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('aksi', 'update');
    $.ajax({ url: 'article_action.php', type: 'POST', data:  formData, contentType: false, processData: false, success: function(resp){ if(resp.trim()==='OK'){ $('#modalEditArticle').addClass('hidden'); location.reload(); }else{ alert(resp); } }, error: function(){ alert('Gagal!'); } });
  });
  $(document).on('click', '.btn-delete-article', function(){
    var id = $(this).data('id');
    if(confirm('Yakin hapus artikel? ')){ $.post('article_action.php', { aksi:'delete', id:id }, function(resp){ if(resp.trim() === 'OK'){ location.reload(); } else { alert(resp); } }); }
  });
});
</script>
</body>
</html>
```