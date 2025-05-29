<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$message = '';

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$stmt = $pdo->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
$existingProfilePic = $user['profile_pic'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = trim($_POST['username']);
    $isUsernameChanged = $newUsername !== $user['username'];
    $hasNewFile = isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE;

    if (!$isUsernameChanged && !$hasNewFile) {
        $message = "Nothing updated.";
    } else {
        if ($hasNewFile && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['profile_pic']['tmp_name'];
            $fileName = basename($_FILES['profile_pic']['name']);
            $fileSize = $_FILES['profile_pic']['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

            if (!in_array($fileExt, $allowed)) {
                $message = "Format gambar tidak diizinkan.";
            } elseif ($fileSize > 500 * 1024) { // 500KB
                $message = "Ukuran gambar maksimal 500KB.";
            } else {
                $newFileName = uniqid('img_') . '.' . $fileExt;
                $uploadPath = $uploadDir . $newFileName;
                move_uploaded_file($fileTmp, $uploadPath);

                if (!empty($existingProfilePic) && file_exists($uploadDir . $existingProfilePic)) {
                    unlink($uploadDir . $existingProfilePic);
                }

                $stmt = $pdo->prepare("UPDATE users SET username = ?, profile_pic = ? WHERE id = ?");
                $stmt->execute([$newUsername, $newFileName, $userId]);
                $_SESSION['username'] = $newUsername;
                $message = "Profile updated.";
                $user['profile_pic'] = $newFileName;
            }
        } elseif ($isUsernameChanged) {
            $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->execute([$newUsername, $userId]);
            $_SESSION['username'] = $newUsername;
            $message = "Profile updated.";
        }
    }

    $user['username'] = $newUsername;
}

$profileImage = 'Assets/default.svg';
if (!empty($user['profile_pic'])) {
    $filePath = $uploadDir . $user['profile_pic'];
    if (file_exists($filePath)) {
        $profileImage = 'uploads/' . $user['profile_pic'] . '?v=' . filemtime($filePath);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @font-face {
            font-family: "ATC Arquette";
            src: url("Fonts/ATCArquette-Regular.otf") format("opentype");
            font-weight: normal;
            font-style: normal;
        }

        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            font-family: "ATC Arquette";
        }

        @media all and (min-width: 100px) {
            .profile-container {
                width: 60%;
            }
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-family: "ATC Arquette";
        }

        .profile-img-preview {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            background: #eee;
            border: 2px solid #9EC6F3;
        }

        .profile-container input[type="text"],
        .profile-container input[type="file"] {
            display: block;
            margin: 10px auto;
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .profile-container button {
            background: #9EC6F3;
            color: #003355;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            display: block;
            margin: 20px auto 0;
            cursor: pointer;
            font-family: "ATC Arquette";
        }

        .profile-container .message {
            text-align: center;
            color: #9EC6F3;
            font-weight: bold;
            font-family: "ATC Arquette";
        }
    </style>
</head>

<body>
    <div class="profile-container" style="color: #003355;">
        <h2>Profile Editor</h2>
        <img src="<?= $profileImage ?>" class="profile-img-preview" alt="Foto Profil">
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            <input type="file" name="profile_pic" accept="image/*">
            <button type="submit">Update</button>
        </form>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <div class="flex justify-center gap-4 mt-6" style="font-family: 'ATC Arquette', sans-serif;">
            <a href="videos.php">
                <button class="bg-red-600 text-[#003355] py-2 px-4 rounded hover:bg-red-700">
                    Back to Videos
                </button>
            </a>
            <form action="logout.php" method="post">
                <button class="bg-red-600 text-[#003355] py-2 px-4 rounded hover:bg-red-700">
                    Log out
                </button>
            </form>
        </div>
    </div>
</body>

</html>
