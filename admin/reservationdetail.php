<?php
session_start();

// Jika belum login sebagai admin, redirect ke halaman login
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/signinuser.php");
    exit;
}


// filepath: c:\xampp\htdocs\projectRBPL\admin\reservationdetail.php
include '../proses/koneksi.php';

// Ambil id reservasi dari URL
$id_reservasi = $_GET['id'] ?? null;
$data = null;

if ($id_reservasi) {
    $query = "SELECT 
        r.id_reservasi,
        r.tanggal_checkin,
        r.tanggal_checkout,
        r.status_konfirmasi,
        k.nomer_kamar AS nama_kamar,
        u.nama_user,
        u.email_user,
        u.tanggallahir_user,
        u.alamat
      FROM reservasi r
      JOIN kamar k ON r.id_kamar = k.id_kamar
      JOIN user u ON r.id_user = u.id_user
      WHERE r.id_reservasi = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_reservasi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reservation Detail</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', sans-serif;
  background-color: #e5e5e5;
}

.container {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  background-color: #d7c1bb;
  width: 350px;
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.profile {
  text-align: center;
  margin-bottom: 40px;
}

.profile .circle {
  width: 100px;
  height: 100px;
  background-color: #bc8f8f;
  border-radius: 50%;
  margin: 0 auto 30px;
  margin-top: 20px;
}

.profile p {
  font-weight: bold;
}

.menu {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.menu-item {
  padding: 10px;
  background-color: #e0d2ce;
  text-decoration: none;
  color: black;
  border-radius: 4px;
  display: flex;
  align-items: center;
  font-weight: 500;
  transition: background 0.2s ease;
}

.menu-item .icon {
  margin-right: 10px;
}

.menu-item:hover,
.menu-item.active {
  background-color: #bc8f8f;
  color: white;
}

.main {
  flex-grow: 1;
  padding: 30px;
  background-color: #e5e5e5;
}

.top-bar {
  display: flex;
  justify-content: flex-end;
}

.logout-btn {
  padding: 8px 16px;
  background-color: #f2f2f2;
  border: 1px solid #333;
  cursor: pointer;
  transition: background 0.3s ease;
}

.logout-btn:hover {
  background-color: #ddd;
}

.main-content {
  flex: 1;
  padding: 30px;
  background-color: #f5f5f5;
}

.header {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 20px;
}

.header h1 {
  flex: 1;
}

.logout {
  background-color: #eee;
  border: 1px solid #ccc;
  padding: 8px 12px;
  cursor: pointer;
}

.reservation-table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  border-radius: 10px;
  overflow: hidden;
}

.reservation-table th, .reservation-table td {
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.reservation-table thead {
  background-color: #b98e88;
  color: white;
}

.not-confirmed {
  color: red;
  font-weight: bold;
}

.confirmed {
  color: green;
  font-weight: bold;
}

.overdue {
  color: orange;
  font-weight: bold;
}

.more-btn {
  background-color: #eee;
  padding: 6px 10px;
  text-decoration: none;
  border-radius: 5px;
  font-weight: bold;
}
.reservation-info {
  margin-top: 20px;
  border: 1px solid #999;
  border-radius: 10px;
  background-color: white;
  padding: 20px;
}

.info-bar {
  display: flex;
  justify-content: space-between;
  background-color: #b98e88;
  color: white;
  padding: 12px 20px;
  border-radius: 10px 10px 0 0;
  font-weight: bold;
}

.not-confirmed {
  color: yellow;
}

.detail-body {
  display: flex;
  padding: 20px;
  gap: 40px;
  border-top: 1px solid #ccc;
  flex-wrap: wrap;
}

.summary-box {
  flex: 1;
  background-color: #f8f8f8;
  padding: 20px;
  border-radius: 10px;
  margin-bottom: 10px;
}

.dates-box {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  background-color: #bc8f8f;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  color: white;
  padding: 15px;
  font-size: medium;
    
}
.room-info {
  background-color: #adacac;
  padding: 10px;
  margin-bottom: 10px;
  color: white;
  font-weight: bold;
  border-radius: 5px;
  text-align: center;
  margin-top: 10px;
}

.price p {
  margin: 4px 0;
}

.price strong {
  display: block;
  margin-top: 10px;
}

.user-info {
  flex: 1;
}

.user-info p {
  margin: 10px 0;
}

.action-buttons {
  margin-top: 20px;
}

.action-buttons button {
  display: block;
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  font-weight: bold;
  border-radius: 20px;
  border: none;
  cursor: pointer;
  box-shadow: 1px 1px 4px rgba(0,0,0,0.2);
}

.accept {
  background-color: white;
  border: 2px solid #28a745;
  color: #28a745;
}

.accept:hover{
  background-color: #28a745;
  color: #beffcd;
}
.decline {
  background-color: white;
  border: 2px solid #dc3545;
  color: #dc3545;
}
.decline:hover{
  background-color: #dc3545;
  color:#ffbfc6;
}
    .detail-box {
      background: #fff;
      border-radius: 10px;
      padding: 32px 40px;
      max-width: 600px;
      margin: 40px auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .detail-box h2 {
      margin-bottom: 24px;
      color: #bc8f8f;
    }
    .detail-row {
      display: flex;
      margin-bottom: 14px;
    }
    .detail-label {
      width: 180px;
      font-weight: bold;
      color: #7a5c58;
    }
    .detail-value {
      flex: 1;
    }
    .action-buttons {
      margin-top: 30px;
      display: flex;
      gap: 16px;
    }
    .accept {
      background: #28a745;
      color: #fff;
      border: none;
      padding: 10px 22px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .decline {
      background: #dc3545;
      color: #fff;
      border: none;
      padding: 10px 22px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .badge {
      padding: 6px 14px;
      border-radius: 6px;
      font-weight: bold;
      font-size: 14px;
    }
    .bg-success {
      background: #28a745;
      color: #fff;
    }
    .bg-warning {
      background: #ffc107;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="profile">
        <div class="circle"></div>
        <p>ADMIN</p>
      </div>
      <nav class="menu">
         <a href="dashboard.php"class="menu-item">
            <i class="icon">🏠</i> Dashboard
        </a>
        <a href="reservation.php" class="menu-item active">
            <i class="icon">🔔</i> Reservation
        </a>
        <a href="room.php" class="menu-item">
             <i class="icon">🚪</i> Room
        </a>
        <a href="invoice.php" class="menu-item">
             <i class="icon">📋</i> Payment
        </a>
     </aside>
    </aside>

    
<main class="main-content">
      <div class="header">
        <h1>RESERVATION DETAIL</h1>
        <div class="top-bar">
        <form action="../proses/logout.php" method="post" >
        <button class="logout-btn" type="submit">Logout</button>
        </form>
      </div>
      </div>
        <tbody>
           <div class="reservation-info">
        <div class="info-bar">
          <span><?= htmlspecialchars($data['nama_kamar'] ?? '') ?></span>
          <span><?= htmlspecialchars($data['nama_user'] ?? '') ?></span>
          <span><?= htmlspecialchars($data['tanggal_checkin'] ?? '') ?></span>
          <span><?= htmlspecialchars($data['tanggal_checkout'] ?? '') ?></span>
          <span class="not-confirmed"><?= htmlspecialchars($data['status_konfirmasi'] ?? '') ?></span>
        </div>

        <div class="detail-body">
          <div class="summary-box">
            <h3>Reservation Summary</h3>
            <div class ="dates-box">
            <div class="dates">
              <div>
                <strong>CHECK IN</strong><br/>
                <?= htmlspecialchars($data['tanggal_checkin'] ?? '') ?>
              </div>
              <div>
                <strong>CHECK OUT</strong><br/>
                <?= htmlspecialchars($data['tanggal_checkout'] ?? '') ?>
              </div>
               <div class="room-info">
              Kost Putri Mbah Dalang<br/>
              <?= htmlspecialchars($data['nama_kamar'] ?? '') ?>
            </div>
            </div>
            </div>
            <h4>Price Summary</h4>
            <div class="price">
              <p>Sub Total: Rp 1.200.000,00 × 3</p>
              <p>Taxes: Rp 0,00</p>
              <strong>Total Price: Rp 3.600.000,00</strong>
            </div>
          </div>

          <div class="user-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($data['nama_user'] ?? '') ?></p>
            <p><strong>E-mail:</strong> <?= htmlspecialchars($data['email_user'] ?? '') ?></p>
            <p><strong>Date of birth:</strong> <?= htmlspecialchars($data['tanggallahir_user'] ?? '') ?></p>
            <p><strong>Address:</strong><?= htmlspecialchars($data['alamat'] ?? '') ?></p>

          
            <div style="display: flex; gap: 20px; margin-top: 20px;">
            <div class="action-buttons">
              <form action="../proses/accept_reservation.php" method="post">
                <input type="hidden" name="id_reservasi" value="<?= htmlspecialchars($id_reservasi) ?>">
                <button type="submit" class="accept">Accept Reservation</button>
              </form>
            </div>
            <div class="action-buttons">
              <form action="../proses/decline_reservation.php" method="post">
                <input type="hidden" name="id_reservasi" value="<?= htmlspecialchars($id_reservasi) ?>">
                <button type="submit" class="decline">Decline Reservation</button>
              </form>
            </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
    <div class="detail-box">
    <h2>Detail Reservasi</h2>
    <?php if($data): ?>
      <div class="detail-row">
        <div class="detail-label">Nama User</div>
        <div class="detail-value"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Email</div>
        <div class="detail-value"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">No. HP</div>
        <div class="detail-value"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Alamat</div>
        <div class="detail-value"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Room</div>
        <div class="detail-value"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Tanggal Check-in</div>
        <div class="detail-value"><?= htmlspecialchars($data['tanggal_checkin'] ?? '') ?></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Tanggal Check-out</div>
        <div class="detail-value"><?= htmlspecialchars($data['tanggal_checkout'] ?? '') ?></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value">
          <?php if(($data['status_konfirmasi'] ?? '') == 'Confirmed'): ?>
            <span class="badge bg-success">Confirmed</span>
          <?php else: ?>
            <span class="badge bg-warning">Not Confirmed</span>
          <?php endif; ?>
        </div>
      </div>
      <?php if($data['status_konfirmasi'] != 'Confirmed'): ?>
      <div class="action-buttons">
        <form action="../proses/accept_reservation.php" method="post">
          <input type="hidden" name="id_reservasi" value="<?= htmlspecialchars($id_reservasi) ?>">
          <button type="submit" class="accept">Accept Reservation</button>
        </form>
      </div>
      <?php endif; ?>
    <?php else: ?>
      <p>Data reservasi tidak ditemukan.</p>
    <?php endif; ?>
    <div style="margin-top:30px;">
      <a href="reservation.php" style="text-decoration:none;color:#bc8f8f;font-weight:bold;">&larr; Kembali ke Daftar Reservasi</a>
    </div>
  </div>
</body>
</html>
