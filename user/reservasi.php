<?php
session_start();
include '../proses/koneksi.php';

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login!'); window.location.href='../login/signinuser.php';</script>";
    exit;
}
$room_id = isset($_GET['room']) ? $_GET['room'] : '';

$nama_kamar = '';

if ($room_id) {
    $query = "SELECT nomer_kamar FROM kamar WHERE nomer_kamar = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $room_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        $nomer_kamar = $data['nomer_kamar'];
        $query1 = "SELECT * FROM kamar WHERE nomer_kamar = '$room_id'";
        $result1 = mysqli_query($koneksi, $query1);
        if (mysqli_num_rows($result1) > 0) {
            $row = mysqli_fetch_assoc($result1);
            $id_kamar = $row['id_kamar'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HOME</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
        height: 100%;
        overflow-y: hidden;
    }

    .content {
        overflow-y: auto;
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

    .content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
      overflow: auto;
    }

    .card-box {
      position: relative;
      border: 1px solid #333;
      padding: 1rem;
      border-radius: 6px;
      max-width: 1100px;
      width: 90%;
      max-height: 500px;
      overflow-y: auto;
    }

    .room-card {
      display: flex;
      gap: 1rem;
      border: 1px solid #ccc;
      padding: 1rem;
      border-radius: 5px;
      margin-bottom: 1rem;
    }

    .room-card img {
      width: 180px;
      height: 120px;
      border-radius: 5px;
      object-fit: cover;
    }

    .room-info h5 {
      font-weight: bold;
    }

    .form-label {
      margin-top: 1rem;
      margin-bottom: 0.5rem;
      font-weight: bold;
    }

    .form-control,
    .form-select {
      background-color: #b58986;
      color: white;
      border: none;
      border-radius: 6px;
    }

    .form-control::placeholder {
      color: white;
    }

    .back-button,
    .next-button {
      font-size: 2rem;
      margin-top: 1rem;
      background: none;
      border: none;
      cursor: pointer;
    }

    .navigation-buttons {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .optionsPerBulan:hover {
      background-color: #333;
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
      <div class="step">
        <div class="number">2</div>
        <div class="label">Confirmation</div>
      </div>
      <div class="step">
        <div class="number">3</div>
        <div class="label">Payment</div>
      </div>
    </div>

    <div class="content" style="margin-top: -120px;">
      <div class="card-box">
        <div class="room-card">
          <img src="../assets/kamar.png" alt="Kamar Kos"/>
          <div class="room-info">
            <h5>Kos Putri Mbah Dalang</h5>
            <div>No. Kamar: <?= htmlspecialchars($room_id) ?></div>
            <p>Fasilitas: AC - Kamar Mandi Dalam, Kloset Duduk, Lemari, dan Kasur</p>
          </div>
        </div>

        <form action="konfirmasi_reservasi.php" method="post">
          <label class="form-label" for="tanggal">Tanggal Masuk:</label>
          <input type="date" id="tanggal" class="form-control" placeholder="dd/mm/yyyy" name="tanggal_checkin" required/>

          <label class="form-label" for="lama">Lama Sewa:</label>
          <select id="lama" class="form-select" name="lama_sewa" required>
          </select>

          <label class="form-label" for="pembayaran">Pembayaran:</label>
          <select id="pembayaran" class="form-select" name="pembayaran" required>
            <option value="Per Bulan">Per Bulan</option>
            <option value="Per Tahun">Per Tahun</option>
          </select>

          <input type="hidden" name="id_kamar" value="<?= $id_kamar ?>">
          <input type="hidden" id="total_harga" name="total_harga" value="">

          <div class="navigation-buttons">
            <a href="room.php">
              <button type="button" class="back-button">&larr;</button>
            </a>
            <button type="submit" class="next-button">&rarr;</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap scripts -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

  <!-- SCRIPT untuk dinamis dropdown "Lama Sewa" dan simpan data ke localStorage -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    const lamaSelect = document.getElementById("lama");
    const pembayaranSelect = document.getElementById("pembayaran");
    const tanggalInput = document.getElementById("tanggal");
    const form = document.getElementById("reservationForm");

    const optionsPerBulan = [
      { value: "1", text: "1 Bulan" },
      { value: "2", text: "2 Bulan" },
      { value: "3", text: "3 Bulan" },
      { value: "6", text: "6 Bulan" }
    ];

    const optionsPerTahun = [
      { value: "1", text: "1 Tahun" },
      { value: "2", text: "2 Tahun" }
    ];

    function updateLamaOptions() {
      const pembayaranValue = pembayaranSelect.value;

      lamaSelect.innerHTML = "";

      let optionsToAdd = [];

      if (pembayaranValue === "Per Bulan") {
        optionsToAdd = optionsPerBulan;
      } else if (pembayaranValue === "Per Tahun") {
        optionsToAdd = optionsPerTahun;
      }

      optionsToAdd.forEach(option => {
        const opt = document.createElement("option");
        opt.value = option.value;
        opt.textContent = option.text;
        lamaSelect.appendChild(opt);
      });
    }

    updateLamaOptions();

    pembayaranSelect.addEventListener("change", updateLamaOptions);

    // Simpan data ke localStorage saat tombol next diklik (submit form)
    form.addEventListener("submit", function(event) {
      event.preventDefault(); // supaya tidak reload halaman

      // Validasi jika tanggal kosong
      if (!tanggalInput.value) {
        alert("Tanggal masuk harus diisi");
        return;
      }

      // Simpan ke localStorage
      localStorage.setItem("tanggalMasuk", tanggalInput.value);
      localStorage.setItem("lamaSewa", lamaSelect.value);
      localStorage.setItem("pembayaran", pembayaranSelect.value);

      // Arahkan ke halaman berikutnya
      window.location.href = "konfirmasi_reservasi.php";
    });
  });
  </script>

  <!-- home.php (pada bagian tombol Book Now) -->
  <div class="col-3 d-flex justify-content-center align-items-center">
    <?php if ($is_available): ?>
      <a href="reservasi.html?room=<?= urlencode($room_id) ?>" style="text-decoration: none;">
        <button type="button" style="margin-left: 100px; background-color: #C29D97; border: none; color: white; padding: 8px 28px; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; transition: background 0.2s;">
          Book Now
        </button>
      </a>
    <?php else: ?>
      <button type="button" disabled style="margin-left: 100px; background-color: #C29D97; border: none; color: white; padding: 8px 28px; border-radius: 6px; font-weight: bold; font-size: 15px; opacity: 0.6; cursor: not-allowed;">
        Book Now
      </button>
    <?php endif; ?>
  </div>
</body>
</html>
