<?php
// filepath: c:\xampp\htdocs\projectRBPL\user\reservation.php
session_start();

include '../proses/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}

$username = $_SESSION['username'];

// Ambil data user dari database
$query = "SELECT * FROM user WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result_user = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result_user);

// Cegah error jika data kosong
if (!$data) {
    echo "<script>alert('Data pengguna tidak ditemukan!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}

$id_user = $data['id_user'];
$nama_lengkap = $data['nama_user'];
$email = $data['email_user'];
$tgl_lahir = $data['tanggallahir_user'];

// Ambil data pembayaran hanya untuk user yang sedang login
$query = "SELECT 
    p.id_pembayaran,
    p.status_pembayaran,
    p.bukti_pembayaran,
    k.nomer_kamar AS nama_kamar,
    r.harga,
    r.tanggal_checkin,
    r.tanggal_checkout
  FROM pembayaran p
  JOIN kamar k ON p.id_kamar = k.id_kamar
  JOIN reservasi r ON p.id_reservasi = r.id_reservasi
  WHERE p.id_user = ?
  ORDER BY p.id_pembayaran DESC";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    .sidebar {
      height: 100vh;
      background-color: #e9e9e9;
      padding: 30px 20px;
    }
    .sidebar .profile {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar .profile .circle {
      width: 70px;
      height: 70px;
      background-color: #c29d97;
      border-radius: 50%;
      margin: 0 auto 10px auto;
    }
    .nav-button {
      width: 100%;
      background-color: #f1f1f1;
      border: none;
      padding: 12px;
      text-align: left;
      margin-bottom: 10px;
      border-radius: 6px;
      font-size: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .nav-button.active {
      background-color: #c29d97;
      color: white;
    }
    .nav-button:hover {
      background-color: #d6b2ad;
    }
    .content {
      padding: 40px;
    }
    .box {
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 30px;
    }
    .section-title {
      font-weight: bold;
      color: #c29d97;
      margin-bottom: 20px;
      font-size: 20px;
    }
    .info-label {
      font-weight: normal;
      color: gray;
    }
    .info-value {
      font-weight: bold;
      color: black;
    }
    .edit-btn {
      float: right;
      background-color: #f1f1f1;
      border: none;
      border-radius: 6px;
      padding: 5px 12px;
      font-size: 14px;
    }
    .edit-btn:hover {
      background-color: #c29d97;
      color: white;
    }
    .back-btn {
      position: absolute;
      bottom: 20px;
      left: 20px;
      font-size: 24px;
      background: none;
      border: none;
      cursor: pointer;
    }
    .modal-content input {
      margin-bottom: 10px;
    }
    .edit-btn {
      float: none;
      display: block;
      width: 100%;
    }
    .sidebar a {
      text-decoration: none !important;
    }
    .sidebar button {
      text-decoration: none !important;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-2 sidebar">
        <div class="profile">
          <div class="circle"></div>
          <p><strong><?php echo $nama_lengkap ?: $username; ?></strong></p>
        </div>
        <a href="personal.php"><button class="nav-button">Personal</button></a>
        <a href="reservation.php"><button class="nav-button active">Reservation</button></a>
        <a href="notification.php"><button class="nav-button">Notification</button></a>
        <a href="home.php"><button class="back-btn"><</button></a>
      </div>
      <!-- Main Content -->
      <div class="col-10 content">
        <!-- Reservation Section -->
        <div class="box">
          <div class="section-title">Reservation</div>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <div class="d-flex align-items-center mb-4">
                <div class="w-100 d-flex justify-content-between">
                  <div>
                    <div><span class="info-label">Room</span><br><span class="info-value"><?= htmlspecialchars($row['nama_kamar']) ?></span></div>
                    <div class="mt-3"><span class="info-label">Price</span><br><span class="info-value">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span></div>
                  </div>
                  <div>
                    <div><span class="info-label">Check In</span><br><span class="info-value"><?= htmlspecialchars($row['tanggal_checkin']) ?></span></div>
                    <div class="mt-3"><span class="info-label">Check Out</span><br><span class="info-value"><?= htmlspecialchars($row['tanggal_checkout']) ?></span></div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="alert alert-info">Belum ada data pembayaran.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>