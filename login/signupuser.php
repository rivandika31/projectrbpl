session_start();
$_SESSION['username'] = $row['username_user'];

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
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

    .btn-custom {
      background-color: white;
      border: 1px solid black;
      color: #C29D97;
      transition: background-color 0.3s ease;
    }

    .btn-custom:hover {
      background-color: #a3746e;
      color: white;
    }
  </style>
</head>
<body>
  <center>
    <div style="margin-top: 150px; color: white; margin-bottom: 30px;">
      <h1>HELLO</h1>
      <p>Selamat datang di Kost Putri Mbah Dalang! Silahkan melakukan pendaftaran untuk kemudahan lainnya.</p>
    </div>

    <form action="../proses/inputsignup.php" method="post">
      <div style="height: 350px; width: 1271px; background-color: white; border-radius: 15px; padding: 50px;">
        <div class="container">
          <div class="row">
            <div class="col-6">
              <p style="text-align: left; margin-left: 20px;">E-mail:</p>
              <input name="email" type="email" required style="width: 570px; border-radius: 5px; background-color: #C29D97; border: 1px solid white; height: 40px;">
            </div>
            <div class="col-6">
              <p style="text-align: left; margin-left: 20px;">Username:</p>
              <input name="username" type="text" required style="width: 570px; border-radius: 5px; background-color: #C29D97; border: 1px solid white; height: 40px;">
            </div>
          </div>
        </div>

        <div class="container mt-3">
          <div class="row">
            <div class="col-6">
              <p style="text-align: left; margin-left: 20px;">Password:</p>
              <input name="password" type="password" required style="width: 570px; border-radius: 5px; background-color: #C29D97; border: 1px solid white; height: 40px;">
            </div>
            <div class="col-6">
              <p style="text-align: left; margin-left: 20px;">Ulangi Password:</p>
              <input name="konfirmasi_password" type="password" required style="width: 570px; border-radius: 5px; background-color: #C29D97; border: 1px solid white; height: 40px;">
            </div>
          </div>
        </div>

        <button type="submit" class="btn-custom" style="height: 45px; width: 150px; margin-top: 20px; border-radius: 8px; margin-bottom: 20px;">
          SIGN UP
        </button>
        <br>
        <a href="signinuser.php" style="text-decoration: none;">sudah punya akun</a>
      </div>
    </form>
  </center>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
