<?php
include 'koneksi.php';
session_start();

$old_username = $_POST['old_username'];
$new_username = $_POST['new_username'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$query = "UPDATE user SET username_user = ?, password_user = ? WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "sss", $new_username, $new_password, $old_username);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['username'] = $new_username;
    echo "<script>alert('Akun berhasil diperbarui.'); window.location.href='../user/personal.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui akun.'); window.location.href='../user/personal.php';</script>";
}
?>
