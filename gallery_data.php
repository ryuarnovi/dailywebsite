<?php
include "connection.php";
$hlm = isset($_POST['hlm']) ? intval($_POST['hlm']) : 1;
$sql = "SELECT * FROM gallery ORDER BY tanggal DESC LIMIT ".(($hlm-1)*9).", 9";
$hasil = $conn->query($sql);

echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">';
if ($hasil && $hasil->num_rows > 0) {
  while ($g = $hasil->fetch_assoc()) {
    echo '
      <div class="bg-white rounded-2xl shadow-lg flex flex-col overflow-hidden">
        <img src="img/'.htmlspecialchars($g["file"]).'" class="h-52 w-full object-cover rounded-t-2xl" alt="'.htmlspecialchars($g["judul"]).'" />
        <div class="p-4 flex-1 flex flex-col">
          <h5 class="font-bold text-lg text-indigo-700 mb-2">'.htmlspecialchars($g["judul"]).'</h5>
          <p class="text-gray-600 mb-2 flex-1">'.htmlspecialchars($g["deskripsi"]).'</p>
          <div class="flex justify-between items-end mt-auto">
            <span class="text-xs text-gray-400">'.htmlspecialchars($g["tanggal"]).'</span>
            <div class="flex gap-2">
              <!-- Edit button with pencil icon -->
              <button class="btn-edit-gallery bg-emerald-500 hover:bg-emerald-700 text-white px-2 py-1 rounded flex items-center justify-center transition"
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
              <!-- Delete button with trash icon -->
              <button class="btn-delete-gallery bg-pink-600 hover:bg-pink-800 text-white px-2 py-1 rounded flex items-center justify-center transition"
                data-id="'.$g['id'].'"
                title="Delete">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2M3 7h18"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    ';
  }
} else {
  echo '<div class="col-span-3 text-center text-gray-400">Belum ada foto di gallery.</div>';
}
echo '</div>';
?>