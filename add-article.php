<?php
session_start();
include "connection.php";
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

$username = $_SESSION["username"] ?? "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul   = trim($_POST["judul"] ?? "");
    $isi     = trim($_POST["isi"] ?? "");
    $tanggal = date('Y-m-d');
    $gambar  = "";

    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] == 0) {
        $targetDir = "img/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $filename = basename($_FILES["gambar"]["name"]);
        $targetFile = $targetDir . $filename;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                $gambar = $filename;
            } else {
                $message = "<div class='text-red-500 mb-3'>Gagal upload gambar!</div>";
            }
        } else {
            $message = "<div class='text-red-500 mb-3'>File harus JPG, PNG, GIF, atau WebP</div>";
        }
    }

    if ($judul && $isi) {
        $stmt = $conn->prepare("INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
        if ($stmt->execute()) {
            $message = "<div class='text-green-600 mb-3'>Artikel berhasil ditambahkan!</div>";
        } else {
            $message = "<div class='text-red-500 mb-3'>Gagal menyimpan artikel.</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='text-red-500 mb-3'>Judul dan isi harus diisi!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tambah Artikel Baru</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-pink-100 to-blue-100 min-h-screen">
  <?php include "navbar.php"; ?>
  <div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-24">
    <h2 class="text-2xl font-extrabold text-pink-700 mb-6">Tambah Artikel Baru</h2>
    <?php echo $message; ?>
    <form method="post" enctype="multipart/form-data" class="space-y-5">
      <div>
        <label class="block font-semibold mb-2 text-pink-700" for="judul">Judul Artikel</label>
        <input type="text" name="judul" id="judul" autocomplete="off"
               class="w-full py-2 px-4 rounded-lg border border-pink-200 focus:border-indigo-400 focus:ring focus:ring-pink-100 focus:outline-none"
               required>
      </div>
      <div>
        <label class="block font-semibold mb-2 text-pink-700" for="isi">Isi Artikel</label>
        <textarea name="isi" id="isi" rows="5"
                  class="w-full py-2 px-4 rounded-lg border border-pink-200 focus:border-indigo-400 focus:ring focus:ring-pink-100 focus:outline-none"
                  required></textarea>
      </div>
      <div>
        <label class="block font-semibold mb-2 text-pink-700" for="gambar">Gambar (optional)</label>
        <input type="file" name="gambar" id="gambar" accept="image/*"
               class="block w-full px-3 py-2 border border-pink-200 rounded-lg file:bg-indigo-100 file:border-none file:rounded-lg file:py-2 file:px-3">
      </div>
      <div>
        <button type="submit"
          class="px-6 py-2 rounded-lg bg-pink-600 text-white font-semibold shadow hover:bg-pink-700">
          Simpan Artikel
        </button>
        <a href="admin.php?page=article"
          class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold shadow hover:bg-gray-300 ml-2">
          Kembali
        </a>
      </div>
    </form>
    <div class="mt-5 text-xs text-gray-500">
      <b>Penulis:</b> <?php echo htmlspecialchars($username); ?><br>
      <b>Tanggal:</b> <?php echo date('Y-m-d'); ?>
    </div>
  </div>
</body>
</html>