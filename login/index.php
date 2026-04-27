<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    // Sanitasi input untuk mencegah SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query mencari user
    $query = "SELECT * FROM users WHERE username='$username' OR email='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan data penting ke session
            $_SESSION['user'] = $row['username'];
            $_SESSION['status'] = "login";
            
            // Arahkan ke Dashboard (Pastikan filenya .php agar session terbaca)
            header("Location: ../dasboard/Dashboard.php"); 
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username atau Email tidak ditemukan!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modern Farm - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-card">
        <div class="white-bg"></div>
        
        <h1 class="modern-farm">
        <img src="asset/daun.jpg" alt="Logo"> Modern Farm</span>
        </h1>

        <div class="form-container">
        <?php if(!empty($error_message)): ?>
        <div class="error-notif">⚠️ <?php echo $error_message; ?></div>
    <?php endif; ?>

            <form action="" method="POST">
    <div class="input-group">
        <label class="label-text">Nama Pengguna/Email</label>
        <input type="text" name="username" class="input-field" placeholder="Masukkan nama pengguna" required>
    </div>

    <div class="input-group">
        <label class="label-text">Kata Sandi</label>
        <input type="password" name="password" class="input-field" placeholder="Masukkan kata sandi" required>
    </div>

    <button type="submit" name="login" class="btn-submit">MASUK</button>
    
    <p style="font-size: 10px; margin-top: 15px; text-align: center;">
        Belum punya akun? <a href="register.php" style="color: #00a63e; font-weight: bold;">Daftar Sekarang</a>
    </p>
</form>
        </div>
    </div>

</body>
</html>