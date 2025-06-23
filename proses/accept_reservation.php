<?php
// filepath: c:\xampp\htdocs\projectRBPL\proses\accept_reservation.php
include 'koneksi.php';

if (isset($_POST['id_reservasi'])) {
    $id = $_POST['id_reservasi'];
    $query = "UPDATE reservasi SET status_konfirmasi = 'Confirmed' WHERE id_reservasi = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}
header("Location: ../admin/reservation.php");
exit;