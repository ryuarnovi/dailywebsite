<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['aksi']) && $_POST['aksi'] == 'delete') {
    $id = $_POST['id'];
    $res = $conn->query("DELETE FROM gallery WHERE id='$id'");
    echo $res ? "OK" : "Gagal: ".$conn->error;
    exit;
  }
    // ========== CREATE / POST ==========
    if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = date("Y-m-d");

        // Handle upload gambar (field="file")
        $filename = "";
        if (!empty($_FILES['file']['name'])) {
            $filename = uniqid()."_".preg_replace('/[^a-zA-Z0-9\._-]/','',$_FILES['file']['name']);
            $target = "img/".$filename;
            if(!move_uploaded_file($_FILES["file"]["tmp_name"], $target)) {
                echo "Gagal upload gambar";
                exit;
            }
        }
        $stmt = $conn->prepare("INSERT INTO gallery (judul, deskripsi, file, tanggal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $judul, $deskripsi, $filename, $tanggal);
        $ok = $stmt->execute();
        echo $ok ? "OK" : "Gagal tambah";
        exit;
    }

    // ========== UPDATE ==========
    if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $setfile = "";
        $params = [$judul, $deskripsi];

        if (!empty($_FILES['file']['name'])) {
            $filename = uniqid()."_".preg_replace('/[^a-zA-Z0-9\._-]/','',$_FILES['file']['name']);
            $target = "img/".$filename;
            move_uploaded_file($_FILES["file"]["tmp_name"], $target);
            $setfile = ", file=?";
            $params[] = $filename;
        }
        $params[] = $id;
        $sql = "UPDATE gallery SET judul=?, deskripsi=?$setfile WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($setfile) {
            $stmt->bind_param("sssi", ...$params);
        } else {
            $stmt->bind_param("ssi", ...$params);
        }
        $ok = $stmt->execute();
        echo $ok ? "OK" : "Gagal Update";
        exit;
    }

}
?>