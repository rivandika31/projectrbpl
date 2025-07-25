<?php
session_start(); // <--- WAJIB ditambahkan paling awal sebelum akses $_SESSION

include '../proses/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}

$username = $_SESSION['username'];
$id_user = $_SESSION['id_user'] ?? null;

// Ambil data user dari database
$query = "SELECT * FROM user WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Cegah error jika data kosong
if (!$data) {
    echo "<script>alert('Data pengguna tidak ditemukan!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}

// Simpan ke variabel
$nama_lengkap = $data['nama_user'];
$email = $data['email_user'];
$tgl_lahir = $data['tanggallahir_user'];


$notifications = [];
if ($id_user) {
    // Ambil reservasi user yang sudah dikonfirmasi
    $query = "SELECT r.id_reservasi, r.tanggal_checkin, r.tanggal_checkout, k.nomer_kamar
              FROM reservasi r
              JOIN kamar k ON r.id_kamar = k.id_kamar
              WHERE r.id_user = ? AND r.status_konfirmasi = 'Confirmed'
              ORDER BY r.id_reservasi DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
}

// Ambil tanggal checkin dari notifikasi terbaru jika ada
$tanggal_masuk = '';
if (count($notifications) > 0) {
    $tanggal_masuk = $notifications[0]['tanggal_checkin'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Personal</title>
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
    .back-btn {
      position: absolute;
      bottom: 20px;
      left: 20px;
      font-size: 24px;
      background: none;
      border: none;
      cursor: pointer;
    }
    .notification-item {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 16px;
  margin-bottom: 15px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  transition: 0.3s ease;
}

.notification-item:hover {
  background-color: #f1eceb;
  border-color: #c29d97;
}

.notif-time {
  font-size: 13px;
  color: #888;
}

.notif-message {
  margin-top: 10px;
  font-size: 14px;
  color: #444;
  line-height: 1.4;
}

    .sidebar a {
      text-decoration: none !important;
    }
    .sidebar button {
      text-decoration: none !important;
    }
</style> 
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-2 sidebar">
        <div class="profile">
          <div class="circle"></div>
          <p><strong><?php echo $nama_lengkap ?: $username; ?></strong></p>
        </div>
        <a href = "personal.php"><button class="nav-button">Personal</button></a>
        <a href = "reservation.php"><button class="nav-button" >Reservation</button></a>
        <a href = "notification.php"><button class="nav-button active">Notification</button></a>
        <a href = "home.php"><button class="back-btn"><</button></a>
      </div>

      <div class="col-10 content">
        <div class="box">
          <div class="section-title">Notification</div>

          <!-- Notifikasi: Pembayaran Berhasil -->
          <?php if (isset($_GET['payment']) && $_GET['payment'] === 'success'): ?>
        <div class="notification-item">
            <div class="d-flex justify-content-between">
              <strong>PAYMENT SUCCESS!</strong>
            </div>
            <p class="notif-message">
              Bukti pembayaran Anda berhasil diupload. Anda bisa menempati kos mulai dari tanggal <strong><?php echo $tanggal_masuk; ?></strong>. Terima kasih telah memilih kos kami!
            </p>
          </div>
      <?php else: ?>
      <?php endif; ?>

    <!-- Notifikasi: Invoice -->


    <!-- Notifikasi: Booking Berhasil -->
     <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $notif): ?>
          <a style="text-decoration: none; color: black;" href="konfirmasi_pembayaran.php?id_reservasi=<?= htmlspecialchars($notif['id_reservasi']) ?>">
    <div class="notification-item">
      <div class="d-flex justify-content-between">
        <strong>BOOKING CONFIRMED!</strong>
      </div>
      <p class="notif-message">
        Congratulations! Your booking has been successfully confirmed for room <strong><?= htmlspecialchars($notif['nomer_kamar']) ?></strong>. Check-in starts on <strong><?= htmlspecialchars($notif['tanggal_checkin']) ?></strong>. Thank you for choosing our boarding house!
      </p>
    </div>
    </a>
    <?php endforeach; ?>
    <?php else: ?>
      
    <?php endif; ?>

    
        
     
  </div>
</div>
</body>
</html>