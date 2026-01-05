<?php
session_start();
session_unset(); // bersihkan semua session
session_destroy();
header("Location: login.php");
exit();
?>