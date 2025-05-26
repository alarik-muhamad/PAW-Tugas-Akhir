<?php
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Di versi demo ini, kita hanya tampilkan pesan.
    $message = 'Jika email terdaftar, instruksi reset telah dikirim ke email Anda.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-200 font-sans">

    <!-- Navbar -->
    <div class="flex items-center justify-between bg-red-700 px-6 py-3 text-white shadow">
        <div class="flex items-center gap-2">
            <img src="Assets/logo.svg" alt="Logo" class="h-8" />
            <span class="font-bold text-lg">TugasAkhir</span>
        </div>
        <div class="text-2xl font-bold">&#9776;</div>
    </div>

    <!-- Form Container -->
    <div class="flex justify-center items-center min-h-[calc(100vh-72px)] p-4">
        <div class="bg-white rounded-xl shadow-lg flex flex-col w-full max-w-[500px] p-8">
            <h2 class="text-2xl font-bold text-red-900 mb-6">Lupa Password</h2>
            <p class="text-sm text-gray-600 mb-4">
                Masukkan email kamu, dan kami akan bantu reset password kamu (simulasi).
            </p>

            <form method="post" class="space-y-4">
                <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded" />
                <button type="submit" class="w-full bg-red-700 text-white py-2 rounded hover:bg-red-800">
                    Kirim Permintaan Reset
                </button>
                <?php if ($message): ?>
                    <p class="text-green-700 text-sm"><?= $message ?></p>
                <?php endif; ?>
            </form>

            <div class="mt-4 text-sm text-center text-red-900">
                <a href="login.php" class="text-blue-800 hover:underline">Kembali ke login</a>
            </div>
        </div>
    </div>

</body>
</html>
