<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PAW Tugas Akhir</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
    body {
      background-color: #FFF1D5;
    }
    .navbar-custom {
      background-color: #BDDDE4;
    }
  </style>
</head>
<body class="text-gray-800">

  <!-- Navbar -->
  <header class="navbar-custom text-white flex justify-between items-center px-6 py-3 fixed w-full z-10">
    <div class="flex items-center gap-2 font-bold">
      <img src="Assets/tol2bang.svg" alt="Logo" class="h-8" />
      <span class="font-semibold">HELLO EDU</span>
    </div>
    <nav class="space-x-4 text-sm">
      <a href="#video" class="hover:underline">Videos</a>
      <a href="#paper" class="hover:underline">Papers</a>
      <a href="login.php" class="hover:underline">Login</a>
      <a href="register.php" class="hover:underline">Register</a>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="h-screen flex flex-col justify-center items-center text-center px-4 pt-20">
    <h1 class="text-4xl md:text-5xl font-bold text-blue-600 mb-4">Selamat Datang di HELLO EDU</h1>
    <p class="text-gray-700 max-w-xl mb-6">
      Jelajahi kumpulan video dan paper untuk mendukung pembelajaran dan inspirasimu.
      Platform ini dirancang untuk eksplorasi konten yang edukatif dan menarik.
    </p>
    <div class="flex gap-4">
<a href="videos.php" class="bg-blue-400 text-white px-6 py-3 rounded hover:bg-blue-500">Lihat Video</a>
<a href="papers.php" class="border border-blue-400 text-blue-600 px-6 py-3 rounded hover:bg-blue-300">Lihat Papers</a>

    </div>
  </section>

  <!-- Video Preview -->
  <section id="video" class="py-16 px-4 bg-[#FBD0D9] text-center">
    <h2 class="text-2xl font-bold text-red-700 mb-8">🎥 Preview Video</h2>
    <div class="bg-white p-6 rounded-xl shadow-md max-w-lg mx-auto">
      <img src="https://img.youtube.com/vi/ePI6USOMHec/hqdefault.jpg" alt="Preview Video" class="rounded shadow-md mb-4 mx-auto">
      <a href="https://www.youtube.com/watch?v=ePI6USOMHec" target="_blank" class="bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700">▶️ Play</a>
    </div>
  </section>

  <!-- Paper Preview -->
  <section id="paper" class="py-16 px-4 bg-[#FBD0D9] text-center">
    <h2 class="text-2xl font-bold text-red-700 mb-8">📄 Preview Paper</h2>
    <div class="bg-white p-6 rounded-xl shadow-md max-w-lg mx-auto">
      <p class="text-gray-700 mb-4">Some computer science issues in ubiquitous computing</p>
      <a href="https://dl.acm.org/doi/abs/10.1145/159544.159617" target="_blank" class="bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 inline-block mb-2">Baca Sekarang</a>
      <br>
      <a href="papers.php" class="text-red-700 underline hover:text-red-900 text-sm">Lihat Semua Papers</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="navbar-custom text-white text-center text-sm py-4">
    &copy; 2025 Edu Eagle. All rights reserved.
  </footer>
</body>
</html>
