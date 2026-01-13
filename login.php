<?php
session_start();
include "connection.php";
$formUser = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["user"] ?? "";
  $password = $_POST["pass"] ?? "";
  $formUser = $username;
  $password_md5 = md5($password);
  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password_md5'";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    $_SESSION["logged_in"] = true;
    $_SESSION["username"] = $username;
    $_SESSION["is_admin"] = ($username === "admin");
    header("Location: index.php");
    exit();
  } else { $error = "Username atau password salah! "; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | My Daily Journal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="img/logo.png">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-pink-100 to-blue-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">
<div class="w-full max-w-sm mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mt-10">
  <div class="flex flex-col items-center">
    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" /><path d="M5.5 19a7.5 7.5 0 0 1 13 0" /></svg>
    <p class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">My Daily Journal</p>
    <hr class="border-gray-200 dark:border-gray-700 w-full mb-4" />
  </div>
  <?php if(isset($error)) echo "<div class='text-red-500 text-center mb-3'>$error</div>"; ?>
  <form id="loginForm" method="post" autocomplete="off" class="space-y-4">
    <input type="text" id="user" name="user" placeholder="Username" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-300" value="<?= htmlspecialchars($formUser); ?>" />
    <input type="password" id="pass" name="pass" placeholder="Password" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-300" />
    <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded-lg transition">Login</button>
    <p id="errorMsg" class="text-center text-sm text-red-500"></p>
  </form>
</div>
<script>
document.getElementById("loginForm").addEventListener("submit", function(event) {
  const user = document.getElementById("user").value.trim();
  const pass = document.getElementById("pass").value.trim();
  const errorMsg = document.getElementById("errorMsg");
  errorMsg.textContent = "";
  if (user === "") { errorMsg.textContent = "Username tidak boleh kosong!"; event.preventDefault(); return; }
  if (pass === "") { errorMsg.textContent = "Password tidak boleh kosong!"; event.preventDefault(); return; }
});
</script>
</body>
</html>