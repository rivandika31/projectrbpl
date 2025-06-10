<?php
session_start();
session_unset();
session_destroy();
header("Location: ../login/signinuser.php"); // Ganti ke halaman login
exit();
?>
