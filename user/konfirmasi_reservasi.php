<?php
session_start();
include '../proses/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login/signinuser.php');
    exit;
}

// Ambil data user
$username = $_SESSION['username'];
$query = "SELECT * FROM user WHERE username_user = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

$nama_lengkap = $data['nama_user'];
$email = $data['email_user'];
$tgl_lahir = $data['tanggallahir_user'];
$hp = $data['nomer_hp'];
$alamat = $data['alamat'];

// Ambil data reservasi dari POST
$id_kamar = $_POST['id_kamar'];
$tanggal_checkin = $_POST['tanggal_checkin'];
$lama_sewa = $_POST['lama_sewa'];
$pembayaran = $_POST['pembayaran'];

// Hitung tanggal checkout
$checkin = new DateTime($tanggal_checkin);
if ($pembayaran == "Per Bulan") {
    $checkout = clone $checkin;
    $checkout->modify("+$lama_sewa month");
} else {
    $checkout = clone $checkin;
    $checkout->modify("+$lama_sewa year");
}
$tanggal_checkout = $checkout->format('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Data Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      overflow-y: hidden;
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
      border: 1px solid #333;
      padding: 20px 30px;
      border-radius: 6px;
      width: 90%;
      max-width: 1100px;
      display: flex;
      justify-content: space-between;
      gap: 2rem;
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

    .form-label {
      margin-top: 5px;
      font-weight: bold;
    }

    .reservation-box {
      border: 1px solid #333;
      padding: 1rem;
      border-radius: 6px;
      width: 300px;
    }

    .reservation-box .box-title {
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .back-button {
      font-size: 2rem;
      margin-top: 1rem;
      background: none;
      border: none;
      cursor: pointer;
    }

    button.book-btn {
      background-color: #b58986;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 0.5rem 1rem;
      margin-top: 5px;
    }

    .form-section {
      flex: 1;
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
      <div class="step">
        <div class="number">3</div>
        <div class="label">Payment</div>
      </div>
    </div>

    <div class="content" style="margin-top: -100px;">
      <div class="card-box">
        <div class="form-section">
          <h5 class="text-center fw-bold mb-3">DATA INFORMATION</h5>
          <form>
            <label class="form-label">Nama Lengkap :</label>
            <input type="text" style= "background-color: #b58986;" id="namaLengkap" class="form-control" placeholder="Nama Lengkap" value="<?= $nama_lengkap ?>"   readonly/> 

            <label class="form-label">E-mail :</label>
            <input type="email" style= "background-color: #b58986;" id="email" class="form-control" placeholder="email@example.com" value="<?= $email ?>"   readonly/>

            <div class="row">
              <div class="col">
                <label class="form-label">Tanggal Lahir :</label>
                <input type="date" style= "background-color: #b58986;" id="tanggalLahir" class="form-control" value="<?= $tgl_lahir ?>"   readonly/>
              </div>
              <div class="col">
                <label class="form-label">Alamat :</label>
                <input type="text" style= "background-color: #b58986;" id="alamat" class="form-control" placeholder="Alamat" value="<?= $alamat ?>"   readonly/>
              </div>
            </div>

            <label class="form-label mt-3">Nomor HP :</label>
            <input type="tel" style= "background-color: #b58986;" id="nomorHP" class="form-control" placeholder="08xxxxxxxxxx" value="<?= $hp ?>"   readonly/>

            <a href="reservasi.php" >
              <button type="button" class="back-button">&larr;</button>
            </a>
          </form>
        </div>

        <div class="reservation-box">
          <div class="box-title">Reservation Summary</div>
          <div class="mb-3">
            <strong>CHECK IN</strong><br>
            <span id="checkInDate">-</span>
          </div>
          <div class="mb-3">
            <strong>CHECK OUT</strong><br>
            <span id="checkOutDate">-</span>
          </div>
          <div class="mb-3 bg-secondary text-white p-2 rounded">
            Kost Putri Mbah Dalang<br>
            (ROOM : A1)
          </div>
          <div class="box-title">Price Summary</div>
          <p id="priceDetail">Sub Total: -<br>Taxes: Rp 0,00</p>
          <p><strong id="totalPrice">Total Price: -</strong></p>
          <form action="../proses/simpan_transaksi.php" method="post">
            <input type="hidden" name="iduser" value="<?= htmlspecialchars($nama_lengkap) ?>">
            <input type="hidden" name="id_kamar" value="<?= htmlspecialchars($id_kamar) ?>">

            <input type="hidden" name="tanggal_checkin" value="<?= htmlspecialchars($tanggal_checkin) ?>">
            <input type="hidden" name="tanggal_checkout" value="<?= htmlspecialchars($tanggal_checkout) ?>">
            <input type="hidden" name="lama_sewa" value="<?= htmlspecialchars($lama_sewa) ?>">
            <input type="hidden" name="pembayaran" value="<?= htmlspecialchars($pembayaran) ?>">
            <button type="submit" class="book-btn w-100">Book</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="infoModalLabel">Informasi</h5>
        </div>
        <div class="modal-body text-center">
          Data information Anda sedang diproses.<br>
          Silakan cek notifikasi untuk update selanjutnya.
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
  window.addEventListener("DOMContentLoaded", () => {
    const hargaPerBulan = 1200000;

    // Ambil data dari form input hidden
    const tanggalCheckin = document.querySelector('input[name="tanggal_checkin"]').value;
    const tanggalCheckout = document.querySelector('input[name="tanggal_checkout"]').value;
    const lamaSewa = parseInt(document.querySelector('input[name="lama_sewa"]').value);
    const pembayaran = document.querySelector('input[name="pembayaran"]').value;

    function formatDateInputToDisplay(inputDate) {
      const date = new Date(inputDate);
      const day = ("0" + date.getDate()).slice(-2);
      const month = ("0" + (date.getMonth() + 1)).slice(-2);
      const year = date.getFullYear();
      return `${day}/${month}/${year}`;
    }

    function formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
      }).format(angka);
    }

    // Tampilkan tanggal
    document.getElementById("checkInDate").textContent = formatDateInputToDisplay(tanggalCheckin);
    document.getElementById("checkOutDate").textContent = formatDateInputToDisplay(tanggalCheckout);

    // Hitung harga
    let total = 0;
    let subTotalText = "";

    if (pembayaran === "Per Bulan") {
      total = hargaPerBulan * lamaSewa;
      subTotalText = `${formatRupiah(hargaPerBulan)} × ${lamaSewa} bulan`;
    } else if (pembayaran === "Per Tahun") {
      total = hargaPerBulan * 12 * lamaSewa;
      subTotalText = `${formatRupiah(hargaPerBulan * 12)} × ${lamaSewa} tahun`;
    }

    // Tampilkan ringkasan harga
    document.getElementById("priceDetail").innerHTML = `Sub Total: ${subTotalText}<br>Taxes: Rp 0,00`;
    document.getElementById("totalPrice").innerHTML = `Total Price: ${formatRupiah(total)}`;

    // Pop up saat Book ditekan
    const bookForm = document.querySelector('.reservation-box form');
    bookForm.addEventListener('submit', function(e) {
      e.preventDefault();
      // Buat overlay popup
      const popup = document.createElement('div');
      popup.style.position = 'fixed';
      popup.style.top = 0;
      popup.style.left = 0;
      popup.style.width = '100vw';
      popup.style.height = '100vh';
      popup.style.background = 'rgba(0,0,0,0.3)';
      popup.style.display = 'flex';
      popup.style.alignItems = 'center';
      popup.style.justifyContent = 'center';
      popup.style.zIndex = 9999;

      const box = document.createElement('div');
      box.style.background = '#fff';
      box.style.padding = '32px 36px';
      box.style.borderRadius = '12px';
      box.style.boxShadow = '0 2px 8px rgba(0,0,0,0.07)';
      box.style.textAlign = 'center';
      box.innerHTML = `
        <div style="font-size:1.2rem;font-weight:bold;margin-bottom:10px;color:#b58986;">
          Reservasi anda sedang diproses admin
        </div>
        <div>Silahkan menunggu konfirmasi di bagian notification.</div>
      `;
      popup.appendChild(box);
      document.body.appendChild(popup);

      setTimeout(() => {
        popup.remove();
        bookForm.submit();
      }, 2000);
    });
  });
</script>

</body>
</html>
