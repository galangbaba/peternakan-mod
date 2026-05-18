<?php
include 'config.php';

$message = "";
$status = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah username/email sudah ada
    $check_query = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
    mysqli_stmt_execute($stmt_check);
    $check_user = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($check_user) > 0) {
        $message = "Username atau Email sudah terdaftar!";
        $status = "error";
    } else {
        // Enkripsi password sebelum simpan ke database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role_default = "user";
        
        $insert_query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "ssss", $username, $email, $hashed_password, $role_default);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $message = "Pendaftaran berhasil! Silakan login.";
            $status = "success";

            header("refresh:2;url=index-user.php");
        } else {
            $message = "Terjadi kesalahan saat mendaftar.";
            $status = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modern Farm - Daftar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-card">
        <div class="white-bg" style="height: 300px; top: 90px;"></div>
        
        <h1 class="modern-farm">
            <span class="text-wrapper">Daftar</span>
            <span class="span">Akun</span>
        </h1>

        <div class="form-container" style="padding-top: 100px;">
            <?php if($message): ?>
                <div class="error-notif" style="color: <?= $status == 'success' ? '#00a63e' : '#ef4444' ?>;">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label class="label-text">Nama Pengguna</label>
                    <input type="text" name="username" class="input-field" placeholder="Username baru" required>
                </div>

                <div class="input-group">
                    <label class="label-text">Email</label>
                    <input type="email" name="email" class="input-field" placeholder="Alamat email" required>
                </div>

                <div class="input-group">
                    <label class="label-text">Kata Sandi</label>
                    <input type="password" name="password" class="input-field" placeholder="Minimal 6 karakter" required>
                </div>

                <button type="submit" name="register" class="btn-submit">DAFTAR</button>
                
                <p style="font-size: 10px; margin-top: 15px; text-align: center;">
                    Sudah punya akun? <a href="login.php" style="color: #00a63e; font-weight: bold;">Login di sini</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>