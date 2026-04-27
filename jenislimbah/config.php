<?php
/**
 * config.php
 * File konfigurasi database untuk Modern Farm
 */

// 1. Definisikan parameter koneksi
$host     = "localhost";
$user     = "root";      // Default user XAMPP/Laragon
$password = "";          // Default password XAMPP/Laragon kosong
$database = "modern_farm_db"; // GANTI sesuai nama database di phpMyAdmin Anda

// 2. Buat koneksi ke MySQL
$conn = mysqli_connect($host, $user, $password, $database);

// 3. Periksa apakah koneksi berhasil
if (!$conn) {
    die("⚠️ Gagal terhubung ke database: " . mysqli_connect_error());
}

// Set charset ke UTF-8 agar karakter khusus terbaca dengan benar
mysqli_set_charset($conn, "utf8");

?>