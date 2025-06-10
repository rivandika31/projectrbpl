<?php
include '../proses/koneksi.php';

// Ambil semua data kamar dari database
$query = "SELECT * FROM kamar ORDER BY nomer_kamar ASC";
$result = mysqli_query($koneksi, $query);

$rooms = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rooms[$row['nomer_kamar']] = $row;
}

// Daftar kamar yang ingin ditampilkan (urut sesuai layout Anda)
$room_ids = [
    ['A1', 'B1'],
    ['A2', 'B2'],
    ['A3', 'B3'],
    ['A4', 'B4'],
    ['A5', 'B5'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ROOM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    /* Styling untuk nav-link */
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
      border-bottom: 2px solid #C29D97;
    }
    .nav-item {
      margin-right: 90px;
    }

    .btn-logout {
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
    
    .custom-navbar {
      background-color: #FFE9E9;
      height: 145px;
      border-bottom: 4px solid rgba(0, 0, 0, 0.188);
    }
    
    .btn-custom {
    background-color: #C29D97;
    color: black;
    }

    .btn-custom:hover {
      background-color: #a87f79;
      color: white;
    }
  </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFE9E9; height: 145px; border-bottom: 4px solid rgba(0, 0, 0, 0.188);">
        <div class="container-fluid">
          <!-- Navbar Items -->
          <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-left: 400px;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active-page" aria-current="page" href="room.php" style=" font-size: 15px;">Room</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="getting-here.html" style=" font-size: 15px;">Getting Here</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="home.php" style=" font-size: 15px;">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="galeries.html" style=" font-size: 15px;">Galleries</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="contactus.html" style=" font-size: 15px;">Contact US</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

  <!-- Konten Utama -->
  <div style="border: 1px solid black; width: 1000px; height: 450px; align-items: center; margin-left: 268px; margin-top: 70px; text-align: center; padding: 30px;">
    <?php foreach ($room_ids as $row): ?>
      <div class="row align-items-start" style="margin-left: 10px; margin-top: 10px;">
        <?php foreach ($row as $room_id): 
          $room = isset($rooms[$room_id]) ? $rooms[$room_id] : null;
          $status = $room ? $room['status'] : 'Unknown';
          $is_available = strtolower($status) === 'available';
        ?>
        <div class="col me-3" style="height: 70px; background-color: #F5F5F5;">
          <div class="container d-flex justify-content-center align-items-center h-100">
            <div class="row w-100 align-items-center">
              <div class="col-3 d-flex justify-content-center align-items-center">
                <h2><?= htmlspecialchars($room_id) ?></h2>
              </div>
              <div class="col-6 d-flex justify-content-center align-items-center">
                <p style="margin:0; font-weight:bold;"><?= htmlspecialchars($status) ?></p>
              </div>
              <div class="col-3 d-flex justify-content-center align-items-center">
                <?php if ($is_available): ?>
                  <a href="reservasi.html">
                    <button type="button" class="btn btn-custom" style="background-color: #C29D97;">Book Now</button>
                  </a>
                <?php else: ?>
                  <button type="button" class="btn btn-custom" style="background-color: #C29D97;" disabled>Book Now</button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
  
  <script>
    function pasdiklik() {
      alert("yakin mau logout dek??")
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
