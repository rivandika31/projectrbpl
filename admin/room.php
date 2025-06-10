<?php
include '../proses/koneksi.php';
$query = "SELECT * FROM kamar ORDER BY nomer_kamar ASC";
$result = mysqli_query($koneksi, $query);

$room_rows = [
    ['B1', 'A1'],
    ['B2', 'A2'],
    ['B3', 'A3'],
    ['B4', 'A4'],
    ['B5', 'A5'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
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

h1 {
  font-size: 28px;
  margin: 20px 0;
  font-weight: 700;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.card {
  background-color: white;
  padding: 70px;
  border-radius: 8px;
  border: 1px solid #999;
  text-align: center;
  min-width: 300px;
}

.card h2 {
  font-size: 28px;
  color: #9c6b61;
  margin-top: 10px;
}

.cta-grid {
  margin-top: 30px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.cta {
  background-color: white;
  border: 1px solid #999;
  padding: 15px;
  border-radius: 8px;
  font-size: 16px;
}

.highlight {
  color: #9c6b61;
  font-weight: bold;
}
.room-list {
  margin-top: 20px;
  display: grid;
  gap: 20px;
}
.room-row {
  display: flex;
  justify-content: space-between;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}
.room-card {
  background-color: white;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
  position: relative;
  display: flex;
  align-items: center;
  gap: 15px;
  flex: 1;
  min-width: 0;
}

.room-box {
  width: 60px;
  height: 60px;
  border: 1px solid #c8a39c;
  color: #c8a39c;
  font-size: 20px;
  font-weight: bold;
  display: flex;
  justify-content: center;
  align-items: center;
}

.room-info {
  flex-grow: 1;
}

.edit-icon {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
  font-size: 14px;
  color: #888;
}

.edit-icon:hover {
  color: #555;
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
        <a href="invoice.php" class="menu-item">
             <i class="icon">📋</i> Invoice
        </a>
     </aside>
    <main class="main">
      <div class="top-bar">
        <button class="logout-btn">Logout</button>
      </div>
      <h1>ROOM</h1>
      <div class="room-list">
      <?php foreach ($room_rows as $row): ?>
        <div class="room-row">
          <?php foreach ($row as $room_id):
            // Cari data kamar yang sesuai
            mysqli_data_seek($result, 0);
            $room = null;
            while ($r = mysqli_fetch_assoc($result)) {
              if ($r['nomer_kamar'] == $room_id) {
                $room = $r;
                break;
              }
            }
            $status = $room ? $room['status'] : '-';
            $tenant = $room ? $room['tenant_name'] : '-';
          ?>
            <div class="room-card">
              <div class="room-box"><?= htmlspecialchars($room_id) ?></div>
              <div class="room-info">
                <div><strong><?= htmlspecialchars($status) ?></strong></div>
                <div>Name: <?= htmlspecialchars($tenant) ?></div>
              </div>
              <div class="edit-icon" onclick="goToRoomDetail('<?= htmlspecialchars($room_id) ?>')">✎</div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
    </main>
  </div>
  <script>
    function goToRoomDetail(roomId) {
      window.location.href = 'room-detail.php?room=' + encodeURIComponent(roomId);
    }
  </script>
</body>
</html>