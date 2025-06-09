<?php
session_start(); // WAJIB ada di baris paling atas!

include 'koneksi.php';

// Ambil username dan password dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Cek user di database
$query = "SELECT * FROM user WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['password_user'])) {
        $_SESSION['username'] = $username; // Simpan username ke session
        echo "<script>alert('Login berhasil!'); window.location.href='../user/home.php';</script>";
        exit;
    } else {
        echo "<script>alert('Password salah!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Username tidak ditemukan!'); window.history.back();</script>";
}
?>
