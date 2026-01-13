
<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include "connection.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gallery - My Daily Journal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    // Prevent flash of unstyled content
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }
  </script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-pink-100 dark:from-gray-900 dark:to-gray-800 min-h-screen text-gray-800 dark:text-gray-100 pt-20 transition-colors duration-300">
<?php include "navbar.php"; ?>
<section class="max-w-5xl mx-auto px-2 py-10">
  <div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-400">Gallery</h1>
    <button onclick="$('#modalTambah').removeClass('hidden');" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow">+ Tambah Foto</button>
  </div>
  <div id="gallery_data"></div>
</section>
<!-- Modal Tambah -->
<div id="modalTambah" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
  <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 w-full max-w-md relative">
    <button class="absolute top-2 right-3 text-gray-400 hover: text-red-500 text-2xl" onclick="$('#modalTambah').addClass('hidden')">&times;</button>
    <form id="formTambahGallery" enctype="multipart/form-data" class="space-y-4">
      <h2 class="text-xl font-bold mb-4 text-blue-600 dark:text-blue-400">Tambah Foto Gallery</h2>
      <div><label class="font-semibold text-blue-700 dark:text-blue-300 block mb-1">Judul</label><input type="text" name="judul" required class="w-full border border-blue-200 dark: border-gray-600 dark: bg-gray-700 dark: text-white rounded px-3 py-2"></div>
      <div><label class="font-semibold text-blue-700 dark:text-blue-300 block mb-1">Deskripsi</label><textarea name="deskripsi" rows="3" required class="w-full border border-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"></textarea></div>
      <div><label class="font-semibold text-blue-700 dark:text-blue-300 block mb-1">Gambar</label><input type="file" name="file" required accept="image/*" class="w-full border border-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-2"></div>
      <div class="text-right">
        <button type="button" onclick="$('#modalTambah').addClass('hidden')" class="bg-gray-100 dark:bg-gray-600 text-blue-600 dark:text-blue-300 px-4 py-2 rounded mr-2">Batal</button>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover: bg-blue-700 font-semibold">Simpan</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit -->
<div id="modalEditGallery" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
  <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 w-full max-w-md relative">
    <button class="absolute top-2 right-3 text-gray-400 hover:text-red-500 text-2xl" onclick="$('#modalEditGallery').addClass('hidden')">&times;</button>
    <form id="formEditGallery" enctype="multipart/form-data" class="space-y-4">
      <h2 class="text-xl font-bold mb-4 text-emerald-600 dark:text-emerald-400">Edit Gallery</h2>
      <input type="hidden" name="id" id="edit_id">
      <div><label class="font-semibold text-blue-700 dark:text-blue-300 block mb-1">Judul</label><input type="text" name="judul" id="edit_judul" required class="w-full border border-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"></div>
      <div><label class="font-semibold text-blue-700 dark:text-blue-300 block mb-1">Deskripsi</label><textarea name="deskripsi" id="edit_deskripsi" rows="3" required class="w-full border border-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"></textarea></div>
      <div><label class="font-semibold text-blue-700 dark:text-blue-300 block mb-1">Ganti Gambar</label><input type="file" name="file" id="edit_file" accept="image/*" class="w-full border border-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-2"><div id="old_gallery_img" class="mt-2"></div></div>
      <div class="text-right">
        <button type="button" onclick="$('#modalEditGallery').addClass('hidden')" class="bg-gray-100 dark:bg-gray-600 text-blue-600 dark:text-blue-300 px-4 py-2 rounded mr-2">Batal</button>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 font-semibold">Update</button>
      </div>
    </form>
  </div>
</div>
<script>
function loadGallery(){ $.get("gallery_data.php", function(data){ $('#gallery_data').html(data); }); }
$(document).ready(function(){
  loadGallery();
  $('#formTambahGallery').on('submit', function(e){ e.preventDefault(); var formData = new FormData(this); formData.append('aksi', 'tambah'); $.ajax({ url: 'gallery_action.php', type: 'POST', data: formData, contentType: false, processData: false, success: function(resp){ $('#modalTambah').addClass('hidden'); $('#formTambahGallery')[0].reset(); loadGallery(); }, error: function(){ alert('Gagal upload!'); } }); });
  $(document).on('click', '.btn-edit-gallery', function(){ $('#edit_id').val($(this).data('id')); $('#edit_judul').val($(this).data('judul')); $('#edit_deskripsi').val($(this).data('deskripsi')); if($(this).data('file')) $('#old_gallery_img').html('<img src="img/'+$(this).data('file')+'" class="h-28 rounded shadow mb-1"/><div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Gambar sekarang</div>'); else $('#old_gallery_img').html(''); $('#modalEditGallery').removeClass('hidden'); });
  $('#formEditGallery').on('submit', function(e){ e.preventDefault(); var formData = new FormData(this); formData.append('aksi', 'update'); $.ajax({ url: 'gallery_action.php', type: 'POST', data: formData, contentType: false, processData: false, success:  function(resp){ $('#modalEditGallery').addClass('hidden'); loadGallery(); }, error: function(){ alert('Update gagal!'); } }); });
  $(document).on('click', '.btn-delete-gallery', function(){ if(confirm('Yakin hapus foto ini?')){ $.post('gallery_action.php', { aksi: 'delete', id: $(this).data('id') }, function(resp){ loadGallery(); }); } });
});
</script>
</body>
</html>