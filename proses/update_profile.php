<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama_user'];
    $email = $_POST['email_user'];
    $tanggal = $_POST['tanggallahir_user'];

    $query = "UPDATE user SET nama_user = ?, email_user = ?, tanggallahir_user = ? WHERE username_user = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $tanggal, $username);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='../user/personal.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>
