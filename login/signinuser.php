<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In</title>
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

    .btn-hover {
      background-color: #C29D97;
      color: white;
      border: none;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-hover:hover {
      background-color: #ffffff;
      transform: scale(1.05);
      color: #C29D97;
    }
  </style>
</head>
<body>
  <center>
    <div style="margin-top: 100px; color: white; margin-bottom: 30px;">
      <h1>HELLO</h1>
      <p>Selamat datang di Kost Putri Mbah Dalang! Silahkan login untuk melanjutkan.</p>
    </div>

    <form action="../proses/loginsignin.php" method="post">
      <div style="height: 350px; width: 800px; background-color: white; border-radius: 15px; padding: 50px; display: flex; justify-content: center; align-items: center;">
        <div style="width: 600px;">
          <p style="text-align: center; font-size: 20px;">Username :</p>
          <input type="text" name="username" required style="width: 100%; border-radius: 5px; background-color: #C29D97; border: 1px solid white; height: 40px; margin-bottom: 20px;">
          <p style="text-align: center; font-size: 20px;">Password :</p>
          <input type="password" name="password" required style="width: 100%; border-radius: 5px; background-color: #C29D97; border: 1px solid white; height: 40px;">
        
      

      <button type="submit" class="btn-hover" style="height: 45px; width: 150px; margin-top: 20px; border-radius: 8px; margin-bottom: 20px">
        SIGN IN
      </button>
      <br>
        <a href="signupuser.php" style="text-decoration: none;">blm punya akun</a>
        </div>
      </div>
    </form>
  </center>
</body>
</html>
