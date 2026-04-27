<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari user berdasarkan username atau email
    $query = "SELECT * FROM users WHERE username='$username' OR email='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password (disarankan menggunakan password_hash di database)
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            header("Location: ../dasboard/Dashboard.html"); // Pindah ke halaman utama jika sukses
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location='login.html';</script>";
    }
}
?>