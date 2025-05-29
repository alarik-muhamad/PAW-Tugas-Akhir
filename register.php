<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config.php';

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $message = 'Konfirmasi password tidak cocok.';
    } else {
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = 'Email sudah terdaftar.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed);
            if ($stmt->execute()) {
                $message = 'Pendaftaran berhasil. Silakan login.';
            } else {
                $message = 'Gagal mendaftar: ' . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @font-face {
            font-family: 'ATC Arquette';
            src: url('Fonts/ATCArquette-Regular.otf') format('opentype');
        }
        * {
            font-family: 'ATC Arquette', sans-serif;
        }
    </style>
</head>
<body class="bg-pink-200 font-sans" style="background-color: #FFF1D5;">

    <!-- Navbar -->
    <div class="flex items-center justify-between bg-red-700 px-6 py-3 text-white shadow" style="background-color: #BDDDE4">
        <div class="flex items-center gap-2">
            <img src="Assets/tol2bang.svg" alt="Logo" class="h-8">
            <span class="font-bold text-lg">HELLO EDU</span>
        </div>
        <div class="text-2xl font-bold">&#9776;</div>
    </div>

    <!-- Form Container -->
    <div class="flex justify-center items-center min-h-[calc(100vh-72px)] p-4">
        <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row w-full max-w-[960px] min-h-[500px] overflow-hidden">
            
            <!-- Panel Ajakan -->
            <div class="w-full md:w-1/2 bg-blue-400 text-white flex flex-col justify-center items-center p-8" style="background-color: #BDDDE4">
                <h3 cclass="text-xl font-bold mb-2 text-center" style="color: #003355;">KAMU SUDAH PUNYA AKUN?</h3>
                <p class="text-sm text-center mb-6" style="color: #003355;">Ayo login dan nikmati pengalaman menonton yang lebih baik!</p>
                <a href="login.php" class="px-6 py-2 rounded font-semibold" style="background-color: #003355; color: white;">MASUK</a>
            </div>

            <!-- Panel Form Register -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-red-900 mb-8" style="color:rgb(68, 96, 153)">Register</h2>
                <form method="post" class="space-y-4">
                    <input type="text" name="username" placeholder="Username" required class="w-full px-4 py-2 border rounded">
                    <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded">
                    <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded">
                    <input type="password" name="confirm" placeholder="Confirm Password" required class="w-full px-4 py-2 border rounded">
                    <button type="submit" class="w-full bg-red-700 text-white py-2 rounded hover:bg-red-800" style="background-color:rgb(68, 96, 153)">DAFTAR</button>
                    <?php if ($message): ?>
                        <p class="text-red-600 text-sm"><?= htmlspecialchars($message) ?></p>
                    <?php endif; ?>
                </form>
                <div class="mt-4 text-sm text-black-900">
                    Sudah punya akun? <a href="login.php" class="text-blue-600 font-medium">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
