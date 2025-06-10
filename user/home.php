<?php
session_start();
include '../proses/koneksi.php';

// Tambahkan ini sebelum HTML
$nama_lengkap = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
          body {
      position: relative;
      z-index: 0;
      margin: 0;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('../assets/rbpl.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      background-color: rgba(0, 0, 0, 0.5);
      background-blend-mode: darken;
      z-index: -1;
    }
        .nav-link {
        font-size: 15px;
        padding-bottom: 5px;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
        }

        .nav-link.active-page {
            border-bottom: 2px solid #C29D97; 
            font-weight: bold;
        }

        .nav-link:hover {
            border-bottom: 2px solid #C29D97; /* Efek hover menyerupai aktif */
        }

        .btn-logout {
          margin-right: 132px;
          padding: 6px 25px;
          border: 1px solid #000;
          background-color: transparent;
          color: #000;
          transition: all 0.3s ease;
        }

        .btn-logout:hover {
          background-color: #6d6d6d;
          color: #fff;
          border-color: #6d6d6d;
          cursor: pointer;
        }

        .nav-item {
            margin-right: 90px;
        }

        .svg-button svg path {
        transition: fill 0.3s ease;
        }

        .svg-button:hover svg path {
        fill: #A0522D; 
        }

        #sidebar {
          position: fixed;
          top: 0;
          left: -270px;
          width: 250px;
          height: 100%;
          background-color: #f3f3f3;
          box-shadow: 2px 0 5px rgba(0,0,0,0.3);
          z-index: 2000;
          transition: left 0.3s ease;
          padding: 20px;
        }

        #overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          display: none;
          z-index: 1500;
        }

        #hamburgerBtn {
          position: fixed;
          top: 20px;
          left: 20px;
          font-size: 28px;
          background: none;
          border: none;
          cursor: pointer;
          z-index: 2100;
        }

        .back-button {
          position: absolute;
          bottom: 20px;
          left: 20px;
          font-size: 20px;
          background-color: #C29D97;
          border: none;
          padding: 8px 12px;
          border-radius: 8px;
          cursor: pointer;
        }

        .sidebar-menu button {
          display: block;
          width: 100%;
          margin: 10px 0;
          background-color: #C29D97;
          color: white;
          border: none;
          padding: 10px;
          border-radius: 6px;
          cursor: pointer;
        }

        .user-name {
          font-weight: bold;
          margin-bottom: 20px;
        }

        .sidebar-menu {
          list-style: none;
          padding: 0;
          margin: 0;
        }

        .sidebar-menu button:hover {
          background-color: #a67c78;
          color: white;
          transform: translateX(5px);
          transition: all 0.3s ease;
        }

        .btn-logout-sidebar {
  display: block;
  width: 100%;
  margin: 10px 0;
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-logout-sidebar:hover {
  background-color: #b52b38;
  transform: translateX(5px);
}

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="height: 145px;">
        <div class="container-fluid">
          <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-left: 400px;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="room.php" style="font-size: 15px; color: white;">Room</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="getting-here.html" style="font-size: 15px; color: white;">Getting Here</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active-page" aria-current="page" href="home.php" style="font-size: 15px; color: white;">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="galeries.html" style="font-size: 15px; color: white;">Galleries</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="contactus.html" style="font-size: 15px; color: white;">Contact  US</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- Sidebar -->
      <div id="sidebar">
        <div class="sidebar-header" style="margin-top: 45px;">
        </div>
        <ul class="sidebar-menu">
          <p><strong>Halo, <?php echo $nama_lengkap ?: $username; ?></strong></p>
          <li>
            <a href="personal.php">
              <button>Personal</button>
            </a>
          </li>
          <li><button>Reservation</button></li>
          <li><button>Notification</button></li>
          <li><button>Settings</button></li>
          <?php if (isset($_SESSION['user'])): ?>
  <li>
    <form action="../proses/logout.php" method="post" style="margin:0; padding:0;">
      <button type="submit" class="btn-logout-sidebar">Logout</button>
    </form>
  </li>
<?php endif; ?>

        </ul>
      </div>

      <!-- Overlay -->
      <div id="overlay" onclick="toggleSidebar()"></div>

      <!-- Hamburger button -->
      <button id="hamburgerBtn" onclick="toggleSidebar()" class="hamburger-btn" style="margin-top: 30px; margin-left: 127px; color: white;">
        ☰
      </button>


      <div style="margin-top: 200px;text-align: center;">
        <h1 style="font-size: 64px; color: white;">KOST PUTRI</h1>
        <h1 style="font-size: 64px; color: white;">MBAH DALANG</h1>
      </div>

      <a href="https://wa.me/6282154571243" target="_blank" class="svg-button" style="background: none; border: none; cursor: pointer; position: absolute; left: 1350px; top: 600px;">
        <svg width="70" height="66" viewBox="0 0 86 82" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.57025e-05 41C3.57025e-05 18.3563 19.2517 0 43 0C66.7483 0 86 18.3563 86 41C86 63.6437 66.7483 82 43 82C35.7269 82 28.8653 80.2751 22.8515 77.2294L5.8726 81.8331C5.0835 82.0464 4.24898 82.0549 3.45524 81.8578C2.66151 81.6608 1.93734 81.2653 1.35752 80.7121C0.777709 80.159 0.363278 79.4683 0.157034 78.7113C-0.0492103 77.9544 -0.0397906 77.1587 0.184321 76.4064L5.01261 60.2202C1.71247 54.3029 -0.0090404 47.7019 3.57025e-05 41ZM24.5715 32.2143C24.5715 32.991 24.8951 33.7359 25.4711 34.2851C26.0471 34.8343 26.8283 35.1429 27.6429 35.1429H58.3572C59.1717 35.1429 59.953 34.8343 60.529 34.2851C61.105 33.7359 61.4286 32.991 61.4286 32.2143C61.4286 31.4376 61.105 30.6927 60.529 30.1435C59.953 29.5943 59.1717 29.2857 58.3572 29.2857H27.6429C26.8283 29.2857 26.0471 29.5943 25.4711 30.1435C24.8951 30.6927 24.5715 31.4376 24.5715 32.2143ZM27.6429 46.8571C26.8283 46.8571 26.0471 47.1657 25.4711 47.7149C24.8951 48.2641 24.5715 49.009 24.5715 49.7857C24.5715 50.5624 24.8951 51.3073 25.4711 51.8565C26.0471 52.4057 26.8283 52.7143 27.6429 52.7143H46.0714C46.886 52.7143 47.6673 52.4057 48.2433 51.8565C48.8193 51.3073 49.1429 50.5624 49.1429 49.7857C49.1429 49.009 48.8193 48.2641 48.2433 47.7149C47.6673 47.1657 46.886 46.8571 46.0714 46.8571H27.6429Z" fill="#C29D97"/>
        </svg>
      </a>

      
      <script>
        function toggleSidebar() {
          const sidebar = document.getElementById("sidebar");
          const overlay = document.getElementById("overlay");
          const hamburger = document.getElementById("hamburgerBtn");

          const isOpen = sidebar.style.left === "0px";

          sidebar.style.left = isOpen ? "-270px" : "0px";
          overlay.style.display = isOpen ? "none" : "block";
          hamburger.style.display = isOpen ? "block" : "none";
        }
      </script>
      
        
      

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>