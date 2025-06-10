<?php
session_start();
include '../proses/koneksi.php';

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}

$username = $_SESSION['username'];

$query = "SELECT * FROM user WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data pengguna tidak ditemukan!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}

$nama_lengkap = $data['nama_user'];
$email = $data['email_user'];
$tgl_lahir = $data['tanggallahir_user'];
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
      <a href = "personal.php"><button class="nav-button active">Personal</button></a>
        <a href = "reservation.php"><button class="nav-button" >Reservation</button></a>
        <a href = "notification.php"><button class="nav-button">Notification</button></a>
        <a href = "settings.php"><button class="nav-button">Settings</button></a>
        <a href = "home.php"><button class="back-btn"><</button></a>
    </div>

    <!-- Main Content -->
    <div class="col-10 content">
      <div class="section-title">Personal</div>
      <div class="box">
        <span class="info-label me-5">Username</span>
        <span class="info-value me-5"><?php echo $username; ?></span>
        <span class="info-label me-5">Password</span>
        <span class="info-value">********</span>
        <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editAccountModal">Edit ✎</button>
      </div>

      <div class="section-title">Information</div>
      <div class="box position-relative">
        <button class="edit-btn position-absolute top-0 end-0 mt-3 me-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
          Edit ✎
        </button>

        <div class="row mt-2">
          <div class="col-md-6">
            <p><span class="info-label">Nama Lengkap</span><br><span class="info-value"><?php echo $nama_lengkap; ?></span></p>
            <p><span class="info-label">E-mail</span><br><span class="info-value"><?php echo $email; ?></span></p>
            <p><span class="info-label">Phone</span><br><span class="info-value">-</span></p>
          </div>
          <div class="col-md-6">
            <p><span class="info-label">Tanggal Lahir</span><br><span class="info-value"><?php echo $tgl_lahir; ?></span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Akun -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="../proses/update_account.php">
      <div class="modal-header">
        <h5 class="modal-title">Edit Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="old_username" value="<?php echo $username; ?>">
        <label>Username Baru:</label>
        <input type="text" class="form-control" name="new_username" value="<?php echo $username; ?>" required>
        <label>Password Baru:</label>
        <input type="password" class="form-control" name="new_password" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="../proses/update_profile.php">
      <div class="modal-header">
        <h5 class="modal-title">Edit Profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="username" value="<?php echo $username; ?>">
        <label>Nama Lengkap:</label>
        <input type="text" class="form-control" name="nama_user" value="<?php echo $nama_lengkap; ?>" required>
        <label>Email:</label>
        <input type="email" class="form-control" name="email_user" value="<?php echo $email; ?>" required>
        <label>Tanggal Lahir:</label>
        <input type="date" class="form-control" name="tanggallahir_user" value="<?php echo $tgl_lahir; ?>" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
