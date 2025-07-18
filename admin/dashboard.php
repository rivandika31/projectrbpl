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
    padding: 20px;
    border-radius: 10px;
    background-color: #fff;
    transition: box-shadow 0.3s ease;
    text-align: center;
    margin: 10px;
  }

  .card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    cursor: pointer;
  }

  a {
    text-decoration: none;
    color: black;
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
</style>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="profile">
        <div class="circle"></div>
        <p>ADMIN</p>
      </div>
      <nav class="menu">
        <a href="#" class="menu-item active"><i class="icon">🏠</i> Dashboard</a>
        <a href="reservation.php" class="menu-item">
            <i class="icon">🔔</i> Reservation
        </a>
        <a href="room.php" class="menu-item">
             <i class="icon">🚪</i> Room
        </a>
        <a href="invoice.php" class="menu-item">
             <i class="icon">📋</i> Payment
        </a>
     </aside>

    <main class="main">
      <div class="top-bar">
        <form action="../proses/logout.php" method="post" >
        <button class="logout-btn" type="submit">Logout</button>
        </form>
      </div>

      <h1>DASHBOARD</h1>

      <div class="stats-grid">
        <a href="room.php" style="text-decoration: none; color: black;">
        <div class="card"><p>Room Booked 🏨</p><h2>0</h2></div>
        </a>
        <a href="room.php" style="text-decoration: none; color: black;">
        <div class="card"><p>Room Available 🏨</p><h2>10</h2></div>
        </a>
        
      </div>

      <div class="cta-grid">
        <a href = "invoice.php"> 
          <div class="cta">Send a <span class="highlight">invoice</span>?</div>  
        </a>
        <a href = "reservation.php">
        <div class="cta">New <span class="highlight">Reservation</span>?</div>
        </a>
      </div>
    </main>
  </div>
</body>
</html>
