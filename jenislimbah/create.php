<?php
include 'config.php';
session_start();

// Proteksi Session
if (!isset($_SESSION['user'])) {
    header("Location: ../login/index.php");
    exit;
}

// Logika Tambah Data
if (isset($_POST['submit_tambah'])) {
    try {
        $sql = "INSERT INTO tb_sumber_ternak (nama_kandang, jenis_ternak, populasi) 
                VALUES (:nama, :jenis, :populasi)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nama'     => $_POST['nama_kandang'],
            ':jenis'    => $_POST['jenis_ternak'],
            ':populasi' => $_POST['populasi']
        ]);
        header("Location: index.php?status=tambah_sukses");
        exit();
    } catch(PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModernFarm - Tambah Sumber Ternak</title>
    
    <link rel="stylesheet" href="../datahewan/globals.css">
    <link rel="stylesheet" href="../datahewan/style.css">
    <link rel="stylesheet" href="../datahewan/style_datahewan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Tambahan style lokal untuk merapikan form */
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #444; }
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-sizing: border-box;
        }
        .btn-save { 
            background: #22C55E; color: white; border: none; padding: 10px 20px; 
            border-radius: 8px; cursor: pointer; font-weight: bold; flex: 1;
        }
        .btn-cancel { 
            background: #EF4444; color: white; text-decoration: none; padding: 10px 20px; 
            border-radius: 8px; text-align: center; font-weight: bold; flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <img src="asset/daun.jpg" alt="Logo"> Modern<span>Farm</span>
            </div>
            <nav class="menu">
                <a href="/peternakan/peternakan/dasboard/index.php" class="menu-item"><img src="asset/iconehome.jpg" alt=""> Dashboard</a>
                <a href="/peternakan/peternakan/datahewan/index.php" class="menu-item"><img src="asset/datahewan.jpg" alt=""> Data Hewan</a>
                <a href="/peternakan/peternakan/jenislimbah/index.php" class="menu-item active"><img src="asset/datapakan.jpg" alt=""> Limbah</a>
                <a href="laporan.php" class="menu-item"><img src="asset/laporan.jpg" alt=""> Laporan</a>
                <a href="pengaturan.php" class="menu-item"><img src="asset/pengaturan.jpg" alt=""> Pengaturan</a>
                <a href="logout.php" class="menu-item"><img src="asset/logout.jpg" alt=""> Logout</a>
            </nav>
        </aside>

        <main class="content">
            <!-- HEADER -->
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

            <!-- FORM SECTION -->
            <div style="display: flex; justify-content: center; padding-top: 40px;">
                <div class="table-card" style="width: 100%; max-width: 500px; padding: 30px; background: white; border-radius: 12px;">
                    <h2 style="margin-bottom: 10px; color: #333;">Tambah Sumber Ternak</h2>
                    <p style="color: #888; font-size: 0.9rem; margin-bottom: 20px;">Masukkan data sumber ternak baru di bawah ini.</p>
                    <hr style="border: 0.5px solid #eee;">

                    <?php if(isset($error)): ?>
                        <div style="color: red; margin-bottom: 15px;">Error: <?= $error ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" style="margin-top: 20px;">
                        <div class="form-group">
                            <label>Nama Kandang</label>
                            <input type="text" name="nama_kandang" required placeholder="Contoh: Kandang Utama">
                        </div>
                        <div class="form-group">
                            <label>Jenis Ternak</label>
                            <select name="jenis_ternak">
                                <option value="Sapi">Sapi</option>
                                <option value="Kambing">Kambing</option>
                                <option value="Ayam">Ayam</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Populasi (Ekor)</label>
                            <input type="number" name="populasi" required placeholder="0">
                        </div>
                        
                        <div style="margin-top: 30px; display: flex; gap: 10px;">
                            <button type="submit" name="submit_tambah" class="btn-save">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="index.php" class="btn-cancel">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>