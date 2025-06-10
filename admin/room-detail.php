<?php
include '../proses/koneksi.php';

// Ambil nomer kamar dari URL
$nomer_kamar = $_GET['room'] ?? '';

if ($nomer_kamar === '') {
    echo "<script>alert('Kamar tidak ditemukan'); window.location.href='room.html';</script>";
    exit;
}

// Ambil data kamar dari database
$query = "SELECT * FROM kamar WHERE nomer_kamar = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $nomer_kamar);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$room = mysqli_fetch_assoc($result);

if (!$room) {
    echo "<script>alert('Data kamar tidak ditemukan'); window.location.href='room.html';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
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
      border: 1px solid #060606;
      padding: 8px 12px;
      cursor: pointer;
    }

    .room-detail-container {
      display: flex;
      position: relative;
      justify-content: center;
      align-items: flex-start;
      background-color: #fff;
      padding: 35px 100px;
      border-radius: 10px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.2);
      gap: 150px;
      margin: 150px auto;
      margin-top: auto;
      max-width: 1100px;
    }

    .room-card-preview {
      text-align: center;
    }

    .kost-title {
      font-weight: bold;
      margin-bottom: 15px;
    }

    .room-box {
      width: 100px;
      height: 120px;
      border: 1px solid #aaa;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .room-label {
      font-size: 32px;
      color: #bc8f8f;
      font-weight: bold;
    }

    .room-id {
      font-weight: bold;
      margin-top: 10px;
    }

    .room-form-wrapper {
      flex: 1;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: bold;
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="date"],
    select {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #aaa;
    }

    .form-row {
      display: flex;
      gap: 20px;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      margin-top: 20px;
    }

    .save-btn {
      padding: 1px 20px;
      background-color: #bc8f8f;
      color: white;
      border: none;
      border-radius: 25px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .save-btn:hover {
      background-color: #946666;
    }

    .close-btn {
      position: absolute;
      top: 15px;
      right: 20px;
      background: none;
      border: none;
      font-size: 24px;
      font-weight: bold;
      color: #999;
      cursor: pointer;
      z-index: 100;
    }

    .close-btn:hover {
      color: #000;
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
        <a href="reservation.php" class="menu-item">
            <i class="icon">🔔</i> Reservation
        </a>
        <a href="room.php" class="menu-item active">
             <i class="icon">🚪</i> Room
        </a>
        <a href="issues.php" class="menu-item">
             <i class="icon">⏱️</i> Issue
        </a>
        <a href="invoice.php" class="menu-item">
             <i class="icon">📋</i> Invoice
        </a>
    </aside>

    <main class="main-content">
      <div class="header">
        <h1>ROOM DETAIL</h1>
        <button class="logout">Logout</button>
      </div>

      <div class="room-detail-container">
        <button class="close-btn" onclick="goBack()">×</button>
        <div class="room-card-preview">
          <p class="kost-title">Kost Putri Mbah Dalang</p>
          <div class="room-box">
            <span class="room-label"><?php echo htmlspecialchars($room['nomer_kamar']); ?></span>
          </div>
          <p class="room-id">ROOM : <span><?php echo htmlspecialchars($room['nomer_kamar']); ?></span></p>
        </div>

        <div class="room-form-wrapper">
          <form method="POST" action="../proses/update_kamar.php">
            <input type="hidden" name="nomer_kamar" value="<?php echo htmlspecialchars($room['nomer_kamar']); ?>">

            <div class="form-group">
              <label>Status</label>
              <select name="status" required>
                <option value="Available" <?php echo $room['status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                <option value="Not Available" <?php echo $room['status'] === 'Not Available' ? 'selected' : ''; ?>>Not Available</option>
              </select>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>From:</label>
                <input type="date" name="start_date" value="<?php echo $room['start_date']; ?>" />
              </div>
              <div class="form-group">
                <label>To:</label>
                <input type="date" name="end_date" value="<?php echo $room['end_date']; ?>" />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Price:</label>
                <input type="text" name="price" value="<?php echo $room['price']; ?>" placeholder="Rp 1.200.000,00 /month" />
              </div>
              <div class="form-group">
                <label>Name:</label>
                <input type="text" name="tenant_name" value="<?php echo $room['tenant_name']; ?>" placeholder="Masukkan nama penyewa" />
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="save-btn">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script>
    function goBack() {
      window.location.href = "room.php";
    }
  </script>
</body>
</html>
