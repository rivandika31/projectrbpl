<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama_user'];
    $email = $_POST['email_user'];
    $tanggal = $_POST['tanggallahir_user'];
    $hp = $_POST['nomer_hp'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE user SET nama_user = '$nama', email_user = '$email', tanggallahir_user = '$tanggal', nomer_hp = '$hp', alamat = '$alamat' WHERE username_user = '$username'";
    $stmt = mysqli_prepare($koneksi, $query);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='../user/personal.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>
