<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<nav class="fixed w-full bg-white dark:bg-gray-900 shadow z-40 top-0 left-0 flex items-center justify-between px-6 py-3 transition-all duration-300">
  <div class="flex items-center gap-3">
    <a href="index.php" class="flex items-center gap-2">
      <img src="https://ui-avatars.com/api/? name=JD&background=6d28d9&color=fff&size=40" alt="Logo" class="w-10 h-10 rounded-full shadow" />
      <span class="font-extrabold text-lg tracking-tight text-indigo-700 dark:text-indigo-300">My Daily Journal</span>
    </a>
  </div>
  <ul class="hidden lg:flex gap-8 ml-10 font-medium text-gray-700 dark:text-gray-200">
    <li><a href="index.php" class="hover:text-indigo-700 dark:hover:text-indigo-400 transition-colors">Home</a></li>
    <li><a href="gallery.php" class="hover:text-indigo-700 dark:hover:text-indigo-400 transition-colors">Gallery</a></li>
    <li><a href="article.php" class="hover:text-indigo-700 dark:hover:text-indigo-400 transition-colors">Article</a></li>
    <?php if ($isAdmin): ?>
      <li><a href="admin.php" class="hover:text-pink-600 dark:hover:text-pink-400 font-bold transition">Admin Panel</a></li>
    <?php endif; ?>
  </ul>
  <div class="flex items-center gap-3">
    <?php if ($isLoggedIn): ?>
      <span class="hidden sm:inline text-gray-600 dark:text-gray-300 mr-2">Hi, <b><?= htmlspecialchars($_SESSION['username']) ?></b></span>
      <a href="logout.php" class="ml-2 px-4 py-1.5 rounded-lg bg-pink-600 text-white font-semibold shadow hover:bg-pink-700 transition">Logout</a>
    <?php else: ?>
      <a href="login.php" class="ml-2 px-4 py-1.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">Login</a>
    <?php endif; ?>
    
    <!-- Toggle Dark Mode Button -->
    <button id="theme-toggle" type="button" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-yellow-300 px-3 py-2 rounded-lg transition hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      <!-- Sun Icon (shown in dark mode) -->
      <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
      </svg>
      <!-- Moon Icon (shown in light mode) -->
      <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
      </svg>
    </button>
  </div>
  
  <button id="navbar-burger" class="lg:hidden flex flex-col justify-center items-center w-9 h-9 ml-2 focus:outline-none" aria-label="Toggle Menu">
    <span class="block w-7 h-1 bg-gray-800 dark:bg-gray-200 mb-1 rounded transition-all"></span>
    <span class="block w-7 h-1 bg-gray-800 dark:bg-gray-200 mb-1 rounded transition-all"></span>
    <span class="block w-7 h-1 bg-gray-800 dark:bg-gray-200 rounded transition-all"></span>
  </button>
</nav>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {}
      }
    }
  </script>

<div id="mobile-menu" class="fixed top-16 left-0 w-full z-30 bg-white dark:bg-gray-900 shadow-lg divide-y rounded-b-xl transition-all duration-300 hidden">
  <ul class="px-4 py-2 flex flex-col gap-2 text-gray-700 dark:text-gray-200">
    <li><a href="index.php" class="block py-2 hover:text-indigo-600 dark:hover:text-indigo-400">Home</a></li>
    <li><a href="article.php" class="block py-2 hover:text-indigo-600 dark:hover:text-indigo-400">Article</a></li>
    <li><a href="gallery.php" class="block py-2 hover:text-indigo-600 dark:hover:text-indigo-400">Gallery</a></li>
    <?php if ($isAdmin): ?>
      <li><a href="admin.php" class="block py-2 text-pink-600 dark:text-pink-400 font-semibold">Admin Panel</a></li>
    <?php endif; ?>
    <?php if ($isLoggedIn): ?>
      <li><span class="px-2 py-1">Hi, <b><?= htmlspecialchars($_SESSION['username']) ?></b></span></li>
      <li><a href="logout.php" class="block py-2 text-pink-600 dark:text-pink-400 font-semibold">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php" class="block py-2 text-indigo-600 dark:text-indigo-400 font-semibold">Login</a></li>
    <?php endif; ?>
  </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // DARK MODE TOGGLE - WORKING VERSION
  var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
  var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
  var themeToggleBtn = document.getElementById('theme-toggle');

  // Change the icons inside the button based on previous settings
  if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
      themeToggleLightIcon.classList.remove('hidden');
      themeToggleDarkIcon.classList.add('hidden');
  } else {
      document.documentElement.classList.remove('dark');
      themeToggleLightIcon.classList.add('hidden');
      themeToggleDarkIcon.classList.remove('hidden');
  }

  themeToggleBtn.addEventListener('click', function() {
    var isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('color-theme', 'light');
      themeToggleLightIcon.classList.add('hidden');
      themeToggleDarkIcon.classList.remove('hidden');
    } else {
      document.documentElement.classList.add('dark');
      localStorage.setItem('color-theme', 'dark');
      themeToggleLightIcon.classList.remove('hidden');
      themeToggleDarkIcon.classList.add('hidden');
    }
  });

  // HAMBURGER MENU
  document.getElementById('navbar-burger').addEventListener('click', function() {
      document.getElementById('mobile-menu').classList.toggle('hidden');
  });

  // Close mobile menu when clicking a link
  document.querySelectorAll('#mobile-menu a').forEach(function(link) {
      link.addEventListener('click', function() {
          document.getElementById('mobile-menu').classList.add('hidden');
      });
  });

  // Close mobile menu on resize
  window.addEventListener('resize', function() {
      if (window.innerWidth >= 1024) {
          document.getElementById('mobile-menu').classList.add('hidden');
      }
  });
});
</script>