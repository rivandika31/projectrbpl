<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login/signinuser.php');
    exit;
}

// Ambil data user dari sesi
$username = $_SESSION['username'];
$query_user = "SELECT id_user FROM user WHERE username_user = ?";
$stmt_user = mysqli_prepare($koneksi, $query_user);
mysqli_stmt_bind_param($stmt_user, "s", $username);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($result_user);
$id_user = $user['id_user'];

// Ambil data dari POST
$id_kamar = $_POST['id_kamar'];
$tanggal_checkin = $_POST['tanggal_checkin'];
$tanggal_checkout = $_POST['tanggal_checkout'];
$pembayaran = $_POST['pembayaran'];
$lama_sewa = (int) $_POST['lama_sewa'];

// Hitung total harga
$harga_per_bulan = 1200000;
if ($pembayaran === "Per Bulan") {
    $total_harga = $harga_per_bulan * $lama_sewa;
} elseif ($pembayaran === "Per Tahun") {
    $total_harga = $harga_per_bulan * 12 * $lama_sewa;
} else {
    $total_harga = 0;
}

// Simpan ke tabel reservasi
$query = "INSERT INTO reservasi (id_user, id_kamar, tanggal_checkin, tanggal_checkout, status_konfirmasi, harga) 
          VALUES (?, ?, ?, ?, 'Not Confirmed', ?)";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "iisss", $id_user, $id_kamar, $tanggal_checkin, $tanggal_checkout, $total_harga);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    // Redirect ke halaman sukses atau home
    header("Location: ../user/home.php");
    exit;
} else {
    echo "Gagal menyimpan reservasi: " . mysqli_error($koneksi);
}
?>
