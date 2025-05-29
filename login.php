<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config.php';

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: videos.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        body, input, button, h2, h3, p, a {
            font-family: "ATC Arquette", sans-serif;
        }
    </style>
</head>
<body class="bg-pink-200" style="background-color: #FFF1D5;">

    <div class="flex items-center justify-between bg-red-700 px-6 py-3 text-white shadow" style="background-color: #BDDDE4;">
        <div class="flex items-center gap-2">
            <img src="Assets/tol2bang.svg" alt="Logo" class="h-8" />
            <span class="font-bold text-lg">HELLO EDU</span>
        </div>
        <div class="text-2xl font-bold">&#9776;</div>
    </div>

    <div class="flex justify-center items-center min-h-[calc(100vh-72px)] p-4">
        <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row w-full max-w-[960px] min-h-[500px] overflow-hidden">
            
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-red-900 mb-8" style="color:rgb(68, 96, 153)">Sign in</h2>
                <form method="post" class="space-y-4">
                    <input type="text" name="username" placeholder="Username" required class="w-full px-4 py-2 border rounded" />
                    <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded" />
                    <div class="text-sm text-right">
                        <a href="forgot-password.php" class="text-blue-800 hover:underline">Lupa password?</a>
                    </div>
                    <button type="submit" class="w-full bg-red-700 text-white py-2 rounded hover:bg-red-800" style="background-color:rgb(68, 96, 153)">LOGIN</button>
                    <?php if ($error): ?>
                        <p class="text-red-600 text-sm"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                </form>
                <div class="mt-4 text-sm text-black-900">
                    Belum punya akun? <a href="register.php" class="text-blue-800 font-medium">Daftar</a>
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-blue-600 text-white flex flex-col justify-center items-center p-8" style="background-color: #BDDDE4;">
                <h3 class="text-xl font-bold mb-2 text-center">KAMU GAK PUNYA AKUN?</h3>
                <p class="text-sm text-center mb-6">Ayo Daftar dulu untuk mendapatkan pengalaman menonton lebih maksimal!</p>
                <a href="register.php" class="bg-white text-blue-600 px-6 py-2 rounded hover:bg-white-100 font-semibold">DAFTAR</a>
            </div>
        </div>
    </div>
</body>
</html>
