<?php
$host = "localhost";         // atau 127.0.0.1
$user = "root";              // sesuaikan dengan user database kamu
$password = "";              // kosongkan jika tidak ada password
$database = "mbahdalang";    // nama database sesuai file SQL

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>