<?php
session_start();

// Jika belum login sebagai admin, redirect ke halaman login
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/signinuser.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<style>* {
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

.logout{
  padding: 8px 16px;
  background-color: #f2f2f2;
  border: 1px solid #333;
  cursor: pointer;
  transition: background 0.3s ease;
}

.logout:hover {
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
  color: #333;
}
</style>
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
        <h1>RESERVATION</h1>
          <form action="../proses/logout.php" method="post">
            <button class="logout-btn" type="submit">Logout</button>
          </form>
      </div>

      <table class="reservation-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Room</th>
            <th>Tanggal Check-in</th>
            <th>Tanggal Check-out</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include '../proses/koneksi.php';

          // Ambil data reservasi beserta nama kamar dan nama user
          $query = "SELECT 
              r.id_reservasi,
              r.tanggal_checkin,
              r.tanggal_checkout,
              r.status_konfirmasi,
              k.nomer_kamar AS nama_kamar,
              u.nama_user
            FROM reservasi r
            JOIN kamar k ON r.id_kamar = k.id_kamar
            JOIN user u ON r.id_user = u.id_user
            ORDER BY r.id_reservasi ASC";
          $result = mysqli_query($koneksi, $query);
          ?>
          <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_user']) ?></td>
            <td><?= htmlspecialchars($row['nama_kamar']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_checkin']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_checkout']) ?></td>
            <td>
              <?php if($row['status_konfirmasi'] == 'Confirmed'): ?>
                <span class="badge bg-success">Confirmed</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">Not Confirmed</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="reservationdetail.php?id=<?= $row['id_reservasi'] ?>" class="more-btn">Detail</a>
            </td>
          </tr>
          <?php endwhile; ?>
          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
