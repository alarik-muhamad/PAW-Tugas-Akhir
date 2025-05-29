<?php
$previewPaper = [
    'url'   => 'https://dl.acm.org/doi/abs/10.1145/159544.159617',
    'desc'  => 'Some computer science issues in ubiquitous computing'
];
$previewVideo = [
    'title' => 'Preview Video',
    'youtubeId' => 'ePI6USOMHec'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>PAW Tugas Akhir</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color:rgb(208, 222, 251);
    }
    .navbar-custom {
      background-color:rgb(77, 59, 178);
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- Navbar -->
  <header class="navbar-custom text-white flex justify-between items-center px-6 py-3">
    <div class="font-bold">Logo<span class="font-semibold">PAW Tugas Akhir</span></div>
    <nav class="space-x-4 text-sm">
      <a href="videos.php" class="hover:underline">Videos</a>
      <a href="papers.php" class="hover:underline">Papers</a>
      <a href="login.php" class="hover:underline">Login</a>
      <a href="register.php" class="hover:underline">Register</a>
    </nav>
  </header>

  <!-- Hero -->
  <main class="flex-grow flex flex-col items-center text-center py-10 px-4">
    <h1 class="text-3xl font-bold text-red-800 mb-4">Selamat Datang di PAW Tugas Akhir</h1>
    <p class="text-gray-700 max-w-xl mb-6">
      Jelajahi kumpulan video dan paper untuk mendukung pembelajaran dan inspirasimu.
      Platform ini dirancang untuk eksplorasi konten yang edukatif dan menarik.
    </p>
    <div class="flex flex-wrap gap-4 justify-center">
      <a href="videos.php" class="bg-red-700 text-white px-5 py-2 rounded hover:bg-red-800 transition">Lihat Video</a>
      <a href="papers.php" class="border border-red-700 text-red-700 px-5 py-2 rounded hover:bg-red-100 transition">Lihat Papers</a>
    </div>

    <!-- Preview Section -->
    <section class="mt-10 w-full max-w-5xl">
      <!-- Video Preview -->
      <div class="bg-white shadow-md rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-red-700 mb-4">🎥 Preview Video</h2>
        <img 
          src="https://img.youtube.com/vi/<?= $previewVideo['youtubeId'] ?>/hqdefault.jpg" 
          alt="Thumbnail Video" 
          class="rounded mb-4 mx-auto w-full max-w-xl"
        >
        <div class="text-center">
          <a 
            href="https://www.youtube.com/watch?v=<?= $previewVideo['youtubeId'] ?>" 
            target="_blank"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 inline-block"
          >
             Play
          </a>
        </div>
      </div>

      <!-- Paper Preview -->
      <div class="bg-white shadow-md rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-red-700 mb-2">📄 Preview Paper</h2>
        <p class="text-sm text-gray-700 mb-3"><?= htmlspecialchars($previewPaper['desc']) ?></p>
        <a 
          href="<?= $previewPaper['url'] ?>" 
          target="_blank" 
          class="inline-block bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700"
        >
          Baca sekarang
        </a>
        <p class="mt-2">
          <a href="papers.php" class="text-red-700 underline hover:text-red-900 text-sm">
            Lihat Semua Papers
          </a>
        </p>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="navbar-custom text-white text-center text-sm py-4">
    &copy; 2025 PAW Tugas Akhir. All rights reserved.
  </footer>

</body>
</html>
