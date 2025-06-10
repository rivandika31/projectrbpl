<?php
include '../proses/koneksi.php';

$nomer_kamar = $_POST['nomer_kamar'];
$status = $_POST['status'];
$tenant = $_POST['tenant_name'];
$price = $_POST['price'];
$start = $_POST['start_date'];
$end = $_POST['end_date'];

$query = "UPDATE kamar SET 
            status = ?, 
            tenant_name = ?, 
            price = ?, 
            start_date = ?, 
            end_date = ?
          WHERE nomer_kamar = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ssssss", $status, $tenant, $price, $start, $end, $nomer_kamar);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Data kamar berhasil diperbarui.'); window.location.href='../admin/room.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui kamar'); history.back();</script>";
}
?>
