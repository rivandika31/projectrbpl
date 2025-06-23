<?php
// filepath: c:\xampp\htdocs\projectRBPL\proses\simpan_reservasi.php
session_start();
include 'koneksi.php';

// Ambil data dari form
$tanggal_checkin = $_POST['tanggal_checkin'];
$tanggal_checkout = $_POST['tanggal_checkout'];
$nomer_kamar = $_POST['nomer_kamar'];

// Ambil id_user dari session login
$id_user = $_SESSION['id_user'] ?? null;

// Cari id_kamar berdasarkan nomer_kamar
$id_kamar = null;
if ($nomer_kamar) {
    $query = "SELECT id_kamar FROM kamar WHERE nomer_kamar = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $nomer_kamar);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        $id_kamar = $data['id_kamar'];
    }
}

// Simpan ke tabel reservasi jika semua data ada
if ($id_user && $id_kamar && $tanggal_checkin && $tanggal_checkout) {
    $query = "INSERT INTO reservasi (id_user, id_kamar, tanggal_checkin, tanggal_checkout, status_konfirmasi) VALUES (?, ?, ?, ?, 'Not Confirmed')";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "iiss", $id_user, $id_kamar, $tanggal_checkin, $tanggal_checkout);
    mysqli_stmt_execute($stmt);

    // Redirect ke halaman sukses/reservasi berikutnya
    header("Location: ../user/konfirmasi_reservasi.php");
    exit;
} else {
    // Jika gagal, kembali ke form dengan pesan error
    header("Location: ../user/reservasi.php?error=1");
    exit;
}