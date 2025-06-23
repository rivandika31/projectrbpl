<?php
session_start();
include '../proses/koneksi.php';

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? null;

// Ambil id_reservasi dari POST atau GET (tergantung alur Anda)
$id_reservasi = $_POST['id_reservasi'] ?? $_GET['id_reservasi'] ?? null;

// Jika tidak ada id_reservasi, ambil reservasi terakhir user
if (!$id_reservasi && $id_user) {
    $q = "SELECT id_reservasi FROM reservasi WHERE id_user = ? ORDER BY id_reservasi DESC LIMIT 1";
    $s = mysqli_prepare($koneksi, $q);
    mysqli_stmt_bind_param($s, "i", $id_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    $d = mysqli_fetch_assoc($r);
    if ($d) $id_reservasi = $d['id_reservasi'];
}

// Ambil data reservasi dan user
$nama_user = $tanggal_checkin = $tanggal_checkout = $harga = '';
if ($id_reservasi) {
    $query = "SELECT 
                r.tanggal_checkin, 
                r.tanggal_checkout, 
                r.harga, 
                u.nama_user
              FROM reservasi r
              JOIN user u ON r.id_user = u.id_user
              WHERE r.id_reservasi = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_reservasi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        $nama_user = $data['nama_user'];
        $tanggal_checkin = $data['tanggal_checkin'];
        $tanggal_checkout = $data['tanggal_checkout'];
        $harga = $data['harga'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Confirmation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      overflow-y: hidden;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .container-full {
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    nav.navbar {
      background-color: #FFE9E9;
      height: 100px;
      border-bottom: 4px solid rgba(0, 0, 0, 0.188);
    }

    .stepper {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin: 1rem 0;
    }

    .step {
      text-align: center;
      min-width: 80px;
    }

    .step .number {
      background-color: #f3e6e4;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: bold;
    }

    .step.active .number {
      background-color: #b58986;
      color: white;
    }

    .step .label {
      font-size: 0.8rem;
      margin-top: 0.5rem;
    }

    .card-box {
      border: 1px solid #333;
      padding: 1rem;
      border-radius: 6px;
      width: 90%;
      max-width: 1200px;
      display: flex;
      justify-content: space-between;
      gap: 2rem;
    }

    .content-box {
      max-width: 1100px;
      width: 90%;
      max-height: 70%;
      margin: auto;
      background-color: white;
      border: 1px solid #333;
      padding: 20px 30px;
      border-radius: 6px;
      margin-top: 3px;
      flex-grow: 1;
      overflow-y: auto;
    }

    .alert-box {
      background-color: #c49a94;
      color: white;
      padding: 20px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }

    .alert-icon {
      font-size: 28px;
      margin-right: 15px;
    }

    .booking-details {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      margin-top: 10px;
    }

    .left-section,
    .right-section {
      flex: 1;
      min-width: 300px;
      position: relative;
    }

    .booking-table td {
      padding: 6px 0;
    }

    .payment-option {
      background-color: #f8f8f8;
      padding: 10px 15px;
      border-radius: 6px;
      margin-bottom: 10px;
      cursor: pointer;
      text-align: center;
      border: 1px solid transparent;
      transition: border-color 0.3s, background-color 0.3s;
      user-select: none;
      font-weight: 600;
    }

    .payment-option:hover,
    .payment-option.active {
      border-color: #b58986;
      background-color: #e9d7d1;
    }

    .btn-pay {
      background-color: black;
      color: white;
      padding: 10px 25px;
      border-radius: 5px;
      float: right;
      border: none;
      cursor: pointer;
      margin-top: 15px;
    }

    .booking-number {
      font-weight: bold;
      text-align: center;
      margin-top: -10px;
      margin-bottom: 20px;
    }

    /* Bank Transfer container styling (kembalikan seperti sebelumnya) */
    .bank-transfer-container {
      border: 1px solid #b58986;
      border-radius: 6px;
      padding: 12px 20px;
      background-color: #f9e6e5;
      font-weight: 600;
      user-select: none;
      margin-top: 10px;
    }

    .bank-card {
      background-color: white;
      border: 1px solid #b58986;
      border-radius: 6px;
      padding: 12px 15px;
      margin-bottom: 10px;
      box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
    }

    /* QRIS container styling seperti Bank Transfer container */
    .qris-container {
      border: 1px solid #b58986;
      border-radius: 6px;
      padding: 12px 20px;
      background-color: #f9e6e5;
      font-weight: 600;
      user-select: none;
      margin-top: 10px;
      text-align: center;
    }

    .qris-container img {
      max-width: 100%;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .qris-container button {
      background-color: #b58986;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 7px 15px;
      cursor: pointer;
      font-weight: 600;
    }

    /* Modal styling */
    #modalBukti {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.4);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    #modalBukti .modal-content {
      background: #fff;
      padding: 30px 24px;
      border-radius: 10px;
      max-width: 350px;
      margin: auto;
      position: relative;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light"></nav>
  <div class="container-full">
    <div class="stepper">
      <div class="step active">
        <div class="number">1</div>
        <div class="label">Dates & Rooms</div>
      </div>
      <div class="step active">
        <div class="number">2</div>
        <div class="label">Information</div>
      </div>
      <div class="step active">
        <div class="number">3</div>
        <div class="label">Payment</div>
      </div>
    </div>
    <div class="content-box" style="height: ">
      <div class="alert-box">
        <div class="alert-icon">✔️</div>
        <div>
          <strong>Your Booking Is Confirmed</strong><br/>
        </div>
      </div>
      <!-- Booking Number -->
      
      <div class="booking-details">
        <div class="left-section">
          <h5>Booking Details</h5>
          <table class="booking-table">
            <tr>
              <td><strong>Nama</strong></td>
              <td style="padding-left: 20px"><?= htmlspecialchars($nama_user) ?></span></td>
            </tr>
            <tr>
              <td><strong>Check In</strong></td>
              <td style="padding-left: 20px"><?= htmlspecialchars($tanggal_checkin) ?></span></td>
            </tr>
            <tr>
              <td><strong>Check Out</strong></td>
              <td style="padding-left: 20px"><?= htmlspecialchars($tanggal_checkout) ?></span></td>
            </tr>
          </table>

          <h5 class="mt-4">Price</h5>
          <table class="booking-table">
            <tr style="font-weight: bold">
              <td><strong id="totalPrice">Total Price  <?= number_format((int)$harga, 0, ',', '.') ?></strong></td>
            </tr>
          </table>
        </div>

        <div class="right-section">
          <h5>Payment Method</h5>
          <div class="payment-option" id="bankBtn">Bank Transfer</div>
          <div class="payment-option" id="qrisBtn">QRIS</div>

           <!-- Bank Transfer Cards Container -->
        <div class="bank-transfer-container" id="bankTransferContainer" style="display: none;">
  <label for="bankList" style="margin-bottom: 8px;">Pilih Bank Tujuan:</label>
  <select id="bankList" class="form-select">
    <option value="" disabled selected>-- Pilih Bank --</option>
    <option value="Seabank - 901482453566">Seabank - 901482453566</option>
    <option value="Dana - 085273078887">Dana - 085273078887</option>
    <option value="ShopeePay - 085273078887">ShopeePay - 085273078887</option>
  </select>
</div>


          <!-- QRIS container (inline, sama dengan Bank Transfer) -->
          <div class="qris-container" id="qrisContainer" style="display: none;">
            <img src="../assets/qris.png" alt="QRIS" />
            <button id="confirmScanBtn">Saya sudah scan</button>
          </div>

          <!-- Tombol Payment Now -->
          <button type="button" class="btn btn-primary w-100 mt-3" id="btnPaymentNow">Payment Now</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Upload Bukti Pembayaran -->
<div id="modalBukti" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; padding:30px 24px; border-radius:10px; max-width:350px; margin:auto; position:relative;">
    <h5 class="mb-3">Upload Bukti Pembayaran</h5>
    <form action="../proses/simpan_pembayaran.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_reservasi" value="<?= htmlspecialchars($id_reservasi ?? '') ?>">
      <div class="mb-3">
        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,application/pdf" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Upload</button>
      <button type="button" class="btn btn-secondary w-100 mt-2" id="closeModal">Batal</button>
    </form>
  </div>
</div>
<script>
document.getElementById('btnPaymentNow').onclick = function() {
  document.getElementById('modalBukti').style.display = 'flex';
};
document.getElementById('closeModal').onclick = function() {
  document.getElementById('modalBukti').style.display = 'none';
};
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script>
  window.addEventListener("DOMContentLoaded", () => {
    const bankBtn = document.getElementById("bankBtn");
    const qrisBtn = document.getElementById("qrisBtn");
    const bankTransferContainer = document.getElementById("bankTransferContainer");
    const qrisContainer = document.getElementById("qrisContainer");
    const confirmScanBtn = document.getElementById("confirmScanBtn");
    const payBtn = document.getElementById("btnPaymentNow");
    const modalBukti = document.getElementById("modalBukti");
    const closeModal = document.getElementById("closeModal");

    function hideAllPaymentDetails() {
      bankTransferContainer.style.display = "none";
      qrisContainer.style.display = "none";
      bankBtn.classList.remove("active");
      qrisBtn.classList.remove("active");
    }

    bankBtn.addEventListener("click", () => {
      if (bankTransferContainer.style.display === "block") {
        hideAllPaymentDetails();
      } else {
        hideAllPaymentDetails();
        bankTransferContainer.style.display = "block";
        bankBtn.classList.add("active");
      }
    });

    qrisBtn.addEventListener("click", () => {
      if (qrisContainer.style.display === "block") {
        hideAllPaymentDetails();
      } else {
        hideAllPaymentDetails();
        qrisContainer.style.display = "block";
        qrisBtn.classList.add("active");
      }
    });

    confirmScanBtn.addEventListener("click", () => {
      alert("QRIS telah dikonfirmasi. Silakan lanjutkan ke upload bukti pembayaran.");
      modalBukti.style.display = "flex";
    });
    

    // Tambahkan event listener untuk tombol upload bukti pembayaran
    document.getElementById("btnUploadBukti").addEventListener("click", () => {
      modalBukti.style.display = "flex";
    });

    // Tutup modal saat klik tombol batal atau di luar modal
    closeModal.addEventListener("click", () => {
      modalBukti.style.display = "none";
    });
    window.addEventListener("click", (event) => {
      if (event.target === modalBukti) {
        modalBukti.style.display = "none";
      }
    });
  });
  </script>

</body>
</html>
