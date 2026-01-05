<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

      if (isset($_POST['aksi']) && $_POST['aksi'] == 'delete') {
    $id = $_POST['id'];
    $res = $conn->query("DELETE FROM article WHERE id='$id'");
    echo $res ? "OK" : "Gagal: ".$conn->error;
    exit;
  }
    // Tambah artikel
    if (isset($_POST['aksi']) && $_POST['aksi']=='tambah') {
        $judul = $_POST['judul'];
        $isi = $_POST['isi'];
        $tanggal = date('Y-m-d');
        $filename = "";
        $username = $_SESSION['username'] ?? "guest"; // sesuaikan sumber usermu
        
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if(!is_dir('img')) mkdir('img', 0777, true);
            $filename = uniqid()."_".preg_replace('/[^a-zA-Z0-9\._-]/','',$_FILES['gambar']['name']);
            $target = "img/".$filename;
            if(!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target)) {
                echo "Gagal upload gambar ke $target";
                exit;
            }
        }
        // PERBAIKI DI BAWAH INI
        $stmt = $conn->prepare("INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $judul, $isi, $filename, $tanggal, $username);
        $ok = $stmt->execute();
        if(!$ok){
            echo "Gagal tambah: ".$stmt->error;
        } else {
            echo "OK";
        }
        exit;
    }

    // UPDATE (opsional)
    if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $isi = $_POST['isi'];
        $params = [$judul, $isi];
        $setfile = "";
        if (!empty($_FILES['gambar']['name'])) {
            $filename = uniqid()."_".preg_replace('/[^a-zA-Z0-9\._-]/','',$_FILES['gambar']['name']);
            $target = "img/".$filename;
            move_uploaded_file($_FILES["gambar"]["tmp_name"], $target);
            $setfile = ", gambar=?";
            $params[] = $filename;
        }
        $params[] = $id;
        $sql = "UPDATE article SET judul=?, isi=?$setfile WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($setfile) {
            $stmt->bind_param("sssi", ...$params);
        } else {
            $stmt->bind_param("ssi", ...$params);
        }
        $ok = $stmt->execute();
        if(!$ok){
            echo "Gagal Update: ".$stmt->error;
        } else {
            echo "OK";
        }
        exit;
    }
    if (isset($_POST['aksi']) && $_POST['aksi'] == 'delete') {
    $id = $_POST['id'];
    $res = $conn->query("DELETE FROM article WHERE id='$id'");
    echo $res ? "OK" : "Gagal: ".$conn->error;
    exit;
}
}
?>