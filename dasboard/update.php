<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModernFarm</title>
    <link rel="styleshet" href="style.css">
</head>
<body>
    
</body>
</html>