<?php
include 'koneksi.php';

// Ambil data dari form
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Cek apakah password dan konfirmasi cocok
if ($password !== $konfirmasi_password) {
    echo "<script>alert('Password dan konfirmasi password tidak sama!'); window.history.back();</script>";
    exit;
}

// Enkripsi password (opsional tapi direkomendasikan)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$query = "INSERT INTO user (email_user, username_user, password_user) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Pendaftaran berhasil!'); window.location.href='../login/signinuser.php';</script>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
