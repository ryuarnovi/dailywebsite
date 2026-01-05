<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";
$hlm = isset($_POST['hlm']) ? intval($_POST['hlm']) : 1;
$sql = "SELECT * FROM article ORDER BY tanggal DESC LIMIT ".(($hlm-1)*9).", 9";
$hasil = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Article - My Daily Journal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-gradient-to-b from-[#e5edf5] to-[#f5e5eb] min-h-screen text-gray-800">
<?php include "navbar.php"; ?>

<section class="max-w-5xl mx-auto px-2 py-16">
  <div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-pink-600">Article</h1>
    <button onclick="$('#modalTambah').removeClass('hidden');"
      class="px-5 py-2 bg-pink-600 text-white font-semibold rounded-lg hover:bg-pink-700 shadow">+ Tambah Artikel</button>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php
    if ($hasil && $hasil->num_rows > 0) {
      while ($row = $hasil->fetch_assoc()) {
        echo '<div class="bg-white rounded-2xl shadow-lg flex flex-col overflow-hidden">';
        // Gambar (cek jika ada)
        if (!empty($row["gambar"]) && file_exists('img/' . $row["gambar"])) {
          echo '<img src="img/'.htmlspecialchars($row["gambar"]).'" class="h-44 w-full object-cover rounded-t-2xl" alt="Gambar">';
        } else {
          echo '<div class="bg-gradient-to-br from-pink-100 to-blue-100 h-44 w-full flex items-center justify-center text-gray-300 text-5xl">?</div>';
        }
        echo '<div class="p-4 flex-1 flex flex-col">';
        echo    '<h5 class="font-bold text-lg text-pink-700 mb-2">'.htmlspecialchars($row["judul"]).'</h5>';
        echo    '<p class="text-gray-700 mb-3 flex-1">'.htmlspecialchars($row["isi"]).'</p>';
        echo    '<div class="flex items-center justify-between mt-auto">';
        echo        '<span class="text-xs text-gray-400">'.htmlspecialchars($row["tanggal"]).'</span>';
        echo        '<div class="flex gap-2">';
        // Edit Button
        echo        '<button type="button" class="btn-edit-article bg-emerald-500 hover:bg-emerald-700 text-white rounded p-2"
                      data-id="'.$row['id'].'"
                      data-judul="'.htmlspecialchars($row['judul']).'"
                      data-isi="'.htmlspecialchars($row['isi']).'"
                      data-gambar="'.htmlspecialchars($row['gambar']).'"
                      title="Edit">';
        echo            '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l9.188-9.188a2 2 0 00-2.828-2.828l-9.188 9.188A1 1 0 004.586 20H4z"/>
                        </svg>
                      </button>';
        // Delete Button
        echo        '<button type="button" class="btn-delete-article bg-pink-600 hover:bg-pink-800 text-white rounded p-2"
                      data-id="'.$row['id'].'" title="Hapus">';
        echo            '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2M3 7h18"/>
                        </svg>
                      </button>';
        echo        '</div></div></div></div>';
      }
    } else {
        echo '<div class="col-span-3 text-center text-gray-400">Belum ada artikel.</div>';
    }
    ?>
  </div>
</section>

<!-- Modal Tambah Artikel -->
<div id="modalTambah" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 hidden">
  <div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-md relative">
    <button class="absolute top-2 right-3 text-gray-400 hover:text-red-500 text-2xl"
      onclick="$('#modalTambah').addClass('hidden')">&times;</button>
    <form id="formTambahArticle" enctype="multipart/form-data" class="space-y-4">
      <h2 class="text-xl font-bold mb-4 text-pink-600">Tambah Artikel</h2>
      <div>
        <label class="font-semibold text-pink-700 block mb-1">Judul</label>
        <input type="text" name="judul" required class="w-full border border-pink-200 rounded px-3 py-2 focus:ring-2 focus:ring-pink-200">
      </div>
      <div>
        <label class="font-semibold text-pink-700 block mb-1">Isi</label>
        <textarea name="isi" rows="3" required class="w-full border border-pink-200 rounded px-3 py-2 focus:ring-2 focus:ring-pink-200"></textarea>
      </div>
      <div>
        <label class="font-semibold text-pink-700 block mb-1">Gambar</label>
        <input type="file" name="gambar" accept="image/*" class="w-full border border-pink-200 rounded px-2 py-2 file:bg-pink-100">
      </div>
      <div class="text-right">
        <button type="button" onclick="$('#modalTambah').addClass('hidden')"
          class="bg-gray-100 text-pink-600 px-4 py-2 rounded hover:bg-gray-200 mr-2">Batal</button>
        <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700 font-semibold">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Artikel (opsional, bisa ditambah sesuai kebutuhan) -->

<script>
// Tambah artikel
$('#formTambahArticle').on('submit', function(e){
  e.preventDefault();
  var formData = new FormData(this);
  formData.append('aksi', 'tambah');
  $.ajax({
    url: 'article_action.php',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function(resp){
      $('#modalTambah').addClass('hidden');
      $('#formTambahArticle')[0].reset();
      location.reload();
    },
    error: function(){ alert('Gagal upload!'); }
  });
});
</script>
</body>
</html>