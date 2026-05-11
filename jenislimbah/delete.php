<?php
// Menggunakan dirname(__DIR__) agar PHP mencari file koneksi.php 
// di folder satu tingkat di atas folder 'datahewan' secara otomatis.
require_once dirname(__DIR__) . '/koneksi.php'; 
session_start();

// Cek apakah ada parameter ID di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    try {
        $id = $_GET['id'];

        // Menyiapkan query DELETE
        $sql = "DELETE FROM tb_hewan WHERE id_hewan = :id";
        $stmt = $conn->prepare($sql);
        
        // Eksekusi dengan binding parameter untuk mencegah SQL Injection
        $stmt->execute([':id' => $id]);

        // Cek apakah ada baris yang terhapus
        if ($stmt->rowCount() > 0) {
            header("Location: index.php?pesan=hapus_sukses");
        } else {
            header("Location: index.php?pesan=data_tidak_ditemukan");
        }
        exit();

    } catch(PDOException $e) {
        // Jika gagal karena masalah database (misal: ID sedang digunakan di tabel lain/foreign key)
        die("Gagal menghapus data: " . $e->getMessage());
    }
} else {
    // Jika mencoba akses langsung tanpa ID, kembalikan ke halaman utama
    header("Location: index.php");
    exit();
}
?>