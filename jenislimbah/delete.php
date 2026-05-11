<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login/index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $sql = "DELETE FROM tb_limter WHERE id_limter = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        header("Location: index.php?status=hapus_sukses");
        exit;
    } catch(PDOException $e) {
        echo "Gagal menghapus: " . $e->getMessage();
    }
    } else {
    header("Location: index.php");
    exit;
}
?>