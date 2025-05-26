<?php
session_start();
session_destroy();
header("Location: login.php?message=logout");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
</head>
<body>
    <p>Anda berhasil logout. <a href="login.php">Login kembali</a></p>
</body>
</html>

