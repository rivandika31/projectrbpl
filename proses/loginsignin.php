<?php
session_start(); // WAJIB ada di baris paling atas!

include 'koneksi.php';

// Ambil username dan password dari form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek admin manual
if ($username === 'admin' && $password === 'adminkos1') {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'admin';
    header("Location: ../admin/dashboard.php");
    exit;
}

// Jika bukan admin, cek ke database user
$query = "SELECT * FROM user WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password_user'])) {
    $_SESSION['username'] = $user['username_user'];
    $_SESSION['id_user'] = $user['id_user'];
    header("Location: ../user/home.php");
    exit;
} else {
    echo "<script>alert('Username atau password salah!');window.location='../login/signinuser.php';</script>";
    exit;
}
?>
