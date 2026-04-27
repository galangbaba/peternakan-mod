<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit;
}

if (isset($_POST['submit_tambah'])) {
    try {
        $sql = "INSERT INTO tb_hewan (id_hewan, jenis_hewan, usia, berat_badan, status_kesehatan) 
                VALUES (:id, :jenis, :usia, :bb, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id'    => $_POST['id'],
            ':jenis'  => $_POST['jenis'],
            ':usia'   => $_POST['usia'],
            ':bb'     => $_POST['bb'],
            ':status' => $_POST['status']
        ]);
        header("Location: datahewan.php?status=tambah_sukses");
        exit();
    } catch(PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModernFarm - Data Hewan</title>
    
    <link rel="stylesheet" href="globals.css">
    <link rel="stylesheet" href="style.css">
    
    <link rel="stylesheet" href="style_datahewan.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="asset/daun.jpg" alt="Logo"> Modern<span>Farm</span>
            </div>
            <nav class="menu">
                <a href="/peternakan/peternakan/dasboard/index.php" class="menu-item"><img src="asset/iconehome.jpg" alt=""> Dashboard</a>
                <a href="/peternakan/peternakan/datahewan/index.php" class="menu-item active"><img src="asset/datahewan.jpg" alt=""> Data Hewan</a>
                <a href="/peternakan/peternakan/jenislimbah/index.php" class="menu-item"><img src="asset/datapakan.jpg" alt=""> Limbah</a>
                <a href="laporan.php" class="menu-item"><img src="asset/laporan.jpg" alt=""> Laporan</a>
                <a href="pengaturan.php" class="menu-item"><img src="asset/pengaturan.jpg" alt=""> Pengaturan</a>
                <a href="logout.php" class="menu-item"><img src="asset/logout.jpg" alt=""> Logout</a>
            </nav>
        </aside>

        <main class="content">
            <header class="header">
                <div class="user-info" style="display: flex; align-items: center; gap: 12px;">
                    <div class="user-text" style="text-align: right;">
                        <span style="display: block; font-size: 0.8rem; color: #888;">User</span>
                        <strong>Amin</strong>
                    </div>
                    <div class="avatar">
                        <img src="asset/profil.jpg" alt="Profile" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                    </div>
                </div>
            </header>

    <div class="container" style="justify-content: center; padding-top: 50px;">
        <div class="table-card" style="width: 100%; max-width: 500px;">
            <h2>Tambah Data Hewan</h2>
            <hr>
            <form action="" method="POST" style="margin-top: 20px;">
                <div class="form-group">
                    <label>ID Hewan</label>
                    <input type="text" name="id" required>
                </div>
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis">
                        <option value="sapi">Sapi</option>
                        <option value="kambing">Kambing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Usia</label>
                    <input type="text" name="usia" required>
                </div>
                <div class="form-group">
                    <label>Berat Badan</label>
                    <input type="text" name="bb" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Healthy">Healthy</option>
                        <option value="Sick">Sick</option>
                    </select>
                </div>
                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" name="submit_tambah" class="btn-save">Simpan</button>
                    <a href="datahewan.php" class="btn-delete" style="text-decoration: none; text-align: center; line-height: 2.5;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>