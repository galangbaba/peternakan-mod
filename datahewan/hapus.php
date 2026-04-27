<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $sql = "DELETE FROM tb_hewan WHERE id_hewan = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        header("Location: datahewan.php?status=hapus_sukses");
    } catch(PDOException $e) {
        echo "Gagal menghapus: " . $e->getMessage();
    }
}
?>