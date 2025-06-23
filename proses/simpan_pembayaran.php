<?php
// filepath: c:\xampp\htdocs\projectRBPL\proses\simpan_pembayaran.php
session_start();
include 'koneksi.php';

$id_reservasi = $_POST['id_reservasi'] ?? '';
$file = $_FILES['bukti_pembayaran'] ?? null;

// Ambil id_user dan id_kamar dari reservasi
$id_user = $id_kamar = null;
if ($id_reservasi) {
    $q = "SELECT id_user, id_kamar FROM reservasi WHERE id_reservasi = ?";
    $s = mysqli_prepare($koneksi, $q);
    mysqli_stmt_bind_param($s, "i", $id_reservasi);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    $d = mysqli_fetch_assoc($r);
    if ($d) {
        $id_user = $d['id_user'];
        $id_kamar = $d['id_kamar'];
    }
}

if ($id_reservasi && $file && $file['error'] == 0 && $id_user && $id_kamar) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $nama_file = 'bukti_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $tujuan = '../uploads/' . $nama_file;

    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        // Simpan ke tabel pembayaran beserta id_user dan id_kamar
        $query = "INSERT INTO pembayaran (id_reservasi, id_user, id_kamar, bukti_pembayaran, status_pembayaran) VALUES (?, ?, ?, ?, 'Menunggu Konfirmasi')";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "iiis", $id_reservasi, $id_user, $id_kamar, $nama_file);
        mysqli_stmt_execute($stmt);

        // Redirect ke notification.php dengan pesan sukses
        header("Location: ../user/notification.php?payment=success");
        exit;
    } else {
        echo "<script>alert('Upload gagal!');window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Data tidak lengkap!');window.history.back();</script>";
    exit;
}
?>