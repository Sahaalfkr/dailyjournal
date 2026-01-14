<?php
include "koneksi.php";

$id = $_POST['id'];

// Get image filename first
$stmt = $conn->prepare("SELECT gambar FROM gallery WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$gambar = $row['gambar'];

// Delete image file
if ($gambar != '') {
    unlink("img/" . $gambar);
}

// Delete from database
$stmt = $conn->prepare("DELETE FROM gallery WHERE id=?");
$stmt->bind_param("i", $id);
$hapus = $stmt->execute();

if ($hapus) {
    echo "Hapus data sukses";
} else {
    echo "Hapus data gagal";
}

$stmt->close();
$conn->close();
?>
