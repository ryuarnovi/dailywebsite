<?php
session_start();
include "connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul   = $_POST["judul"];
    $isi     = $_POST["isi"];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION["username"] ?? "guest";
    $gambar  = "";

    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] == 0) {
        $targetDir = "img/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $filename = basename($_FILES["gambar"]["name"]);
        $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $targetFile = $targetDir . uniqid() . ".$fileType";
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                $gambar = basename($targetFile);
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "ERROR";
    }
    $stmt->close();
}
?>