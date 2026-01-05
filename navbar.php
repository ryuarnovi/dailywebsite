<?php
// Pastikan session_start() sudah dijalankan di file utama sebelum include navbar.php
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$isAdmin    = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<nav class="fixed w-full bg-white/95 shadow z-40 top-0 left-0 flex items-center justify-between px-6 py-3 transition-colors duration-300">
  <!-- Logo Section -->
  <div class="flex items-center gap-3">
    <a href="index.php" class="flex items-center gap-2">
      <img src="https://ui-avatars.com/api/?name=JD&background=6d28d9&color=fff&size=40" alt="Logo" class="w-10 h-10 rounded-full shadow" />
      <span class="font-extrabold text-lg tracking-tight text-indigo-700">My Daily Journal</span>
    </a>
  </div>

  <!-- Desktop Nav Menu -->
  <ul class="hidden lg:flex gap-8 ml-10 font-medium">
    <li><a href="index.php" class="hover:text-indigo-700 transition-colors">Home</a></li>
    <!-- <li><a href=dashboard.php" class="hover:text-indigo-700 transition-colors">Dashboard</a></li> -->
    <li><a href="gallery.php" class="hover:text-indigo-700 transition-colors">Gallery</a></li>
    <li><a href="article.php" class="hover:text-indigo-700 transition-colors">Article</a></li>
    <?php if ($isAdmin): ?>
      <li><a href="admin.php" class="hover:text-pink-600 font-bold transition">Admin Panel</a></li>
    <?php endif; ?>
  </ul>

  <!-- Right Section (Login/Logout) -->
  <div class="flex items-center gap-3">
    <?php if ($isLoggedIn): ?>
      <span class="hidden sm:inline text-gray-600 mr-2">Hi, <b><?= htmlspecialchars($_SESSION['username']) ?></b></span>
      <a href="logout.php" class="ml-2 px-4 py-1.5 rounded-lg bg-pink-600 text-white font-semibold shadow hover:bg-pink-700 transition-colors duration-200">Logout</a>
    <?php else: ?>
      <a href="login.php" class="ml-2 px-4 py-1.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition-colors duration-200">Login</a>
    <?php endif; ?>
  </div>

  <!-- Hamburger for Mobile -->
  <button id="navbar-burger" class="lg:hidden flex flex-col justify-center items-center w-9 h-9 ml-2 focus:outline-none" aria-label="Toggle Menu">
    <span class="block w-7 h-1 bg-gray-800 mb-1 rounded transition-all"></span>
    <span class="block w-7 h-1 bg-gray-800 mb-1 rounded transition-all"></span>
    <span class="block w-7 h-1 bg-gray-800 rounded transition-all"></span>
  </button>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed top-16 left-0 w-full z-30 bg-white/95 shadow-lg divide-y rounded-b-xl transition-all duration-300 hidden">
  <ul class="px-4 py-2 flex flex-col gap-2">
    <li><a href="index.php#home" class="block py-2 text-base hover:text-indigo-600">Home</a></li>
    <!-- <li><a href="index.php#dashboard" class="block py-2 text-base hover:text-indigo-600">Dashboard</a></li> -->
    <li><a href="index.php#article" class="block py-2 text-base hover:text-indigo-600">Article</a></li>
    <li><a href="index.php#gallery" class="block py-2 text-base hover:text-indigo-600">Gallery</a></li>
    <?php if ($isAdmin): ?>
      <li><a href="admin.php" class="block py-2 text-base text-pink-600 font-semibold">Admin Panel</a></li>
    <?php endif; ?>
    <?php if ($isLoggedIn): ?>
      <li><span class="px-2 py-1 text-gray-600">Hi, <b><?= htmlspecialchars($_SESSION['username']) ?></b></span></li>
      <li><a href="logout.php" class="block py-2 text-base text-pink-600 font-semibold">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php" class="block py-2 text-base text-indigo-600 font-semibold">Login</a></li>
    <?php endif; ?>
  </ul>
</div>

<script>
  // Responsive hamburger mobile menu toggle
  const burger = document.getElementById("navbar-burger");
  const mobileMenu = document.getElementById("mobile-menu");
  burger.addEventListener("click", function (){
    mobileMenu.classList.toggle("hidden");
  });
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
    });
  });
  window.addEventListener("resize", ()=>{
    if(window.innerWidth >= 1024){
      mobileMenu.classList.add('hidden');
    }
  });
</script>