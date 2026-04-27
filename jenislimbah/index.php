<?php
// 1. KONEKSI & LOGIKA PROSES
session_start();
include 'config.php';

//agar jika tidak login dulu tidak bisa masuk ke form ini
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit;
}

$status_msg = "";

// --- PROSES SIMPAN DATA ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_sumber'])) {
    $nama_kandang = mysqli_real_escape_string($conn, $_POST['nama_kandang']);
    $jenis_ternak = mysqli_real_escape_string($conn, $_POST['jenis_ternak']);
    $populasi     = mysqli_real_escape_string($conn, $_POST['populasi']);

    $query = "INSERT INTO sumber_ternak (nama_kandang, jenis_ternak, populasi) 
              VALUES ('$nama_kandang', '$jenis_ternak', '$populasi')";

    if (mysqli_query($conn, $query)) {
        $status_msg = "<div class='alert success'>✅ Data ternak berhasil disimpan!</div>";
    } else {
        $status_msg = "<div class='alert error'>❌ Gagal: " . mysqli_error($conn) . "</div>";
    }
}

// --- LOGIKA HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM sumber_ternak WHERE id = '$id'");
    header("Location: limbahternak.php");
    exit();
}

// --- AMBIL DATA UNTUK TABEL ---
$result = mysqli_query($conn, "SELECT * FROM sumber_ternak ORDER BY id DESC");
$data_limbah = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data_limbah[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Farm - Identitas Ternak</title>
    <link rel="stylesheet" href="globals.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Table Styles */
        .table-container {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { text-align: left; padding: 12px 15px; border-bottom: 1px solid #eee; }
        table th { background-color: #f8f9fa; color: #555; font-weight: 600; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; background: #e8f5e9; color: #2e7d32; font-weight: bold; }
        .bg-mint { background-color: #f9fdfb; }

        /* Action Bar Styles */
        .action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-add { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-add:hover { background: #219150; }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .modal-content { background: white; margin: 10% auto; padding: 30px; width: 400px; border-radius: 12px; animation: fadeIn 0.3s ease; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-save { width: 100%; background: #27ae60; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <div class="logo">
            <img src="asset/daun.jpg" alt="Logo"> Modern<span>Farm</span>
        </div>
        <nav class="menu">
            <a href="/peternakan/peternakan/dasboard/index.php" class="menu-item"><img src="asset/iconehome.jpg" alt=""> Dashboard</a>
            <a href="/peternakan/peternakan/datahewan/index.php" class="menu-item"><img src="asset/datahewan.jpg" alt=""> Data Hewan</a>
            <a href="/peternakan/peternakan/jenislimbah/index.php" class="menu-item active"><img src="asset/datapakan.jpg" alt=""> Limbah</a>
            <a href="laporan.php" class="menu-item"><img src="asset/laporan.jpg" alt=""> Laporan</a>
            <a href="pengaturan.php" class="menu-item"><img src="asset/pengaturan.php" alt=""> Pengaturan</a>
            <a href="logout.php" class="menu-item"><img src="asset/logout.jpg" alt=""> Logout</a>
        </nav>
    </aside>

    <main class="content">
        <header class="header" style="display: flex; justify-content: flex-end; align-items: center; padding: 15px 20px; border-bottom: 2px solid #eee; margin-bottom: 20px;">
            <div class="user-info" style="display: flex; align-items: center; gap: 12px;">
                <div class="user-text" style="text-align: right;">
                    <span style="display: block; font-size: 0.8rem; color: #888;">User</span>
                    <strong style="font-size: 1rem; color: #333;">Amin</strong>
                </div>
                <div class="avatar">
                    <img src="asset/profil.jpg" alt="Profile" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #f0f0f0;">
                </div>
            </div>
        </header>

        <section class="table-section">
            <?php echo $status_msg; ?>
            <div class="action-bar">
                <div class="search-wrapper">
                    <input type="text" placeholder="Search by ID, type, or status...">
               </div>
                <button class="btn-add" onclick="bukaModal()"><i class="fas fa-plus"></i> TAMBAH</button>
            </div>

                        <div class="table-container">
                <h3>Daftar Sumber Ternak</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kandang</th>
                            <th>Jenis Ternak</th>
                            <th>Populasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Pastikan data ada dan merupakan array sebelum dilooping
                        if (!empty($data_limbah) && is_array($data_limbah)): 
                            // Opsional: Jika data benar-benar duplikat dari database, 
                            // Anda bisa memfilternya di sini menggunakan array_unique jika perlu.
                            foreach($data_limbah as $index => $limter): 
                        ?>
                            <tr class="<?= ($index % 2 != 0) ? 'bg-mint': ''; ?>">
                                <td><?= $index + 1; ?></td>
                                <td><strong><?= htmlspecialchars($limter['nama_kandang']); ?></strong></td>
                                <td><span class="badge"><?= htmlspecialchars($limter['jenis_ternak']); ?></span></td>
                                <td><?= number_format($limter['populasi'], 0, ',', '.'); ?> Ekor</td>
                                <td>
                                    <a href="edit.php?id=<?= $limter['id']; ?>" style="color: orange; margin-right: 15px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?hapus=<?= $limter['id']; ?>" style="color: #e74c3c;" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            endforeach; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">Belum ada data.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div id="modalTambah" class="modal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Tambah Ternak</h2>
            <span style="cursor:pointer; font-size: 24px;" onclick="tutupModal()">&times;</span>
        </div>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Kandang / ID</label>
                <input type="text" name="nama_kandang" placeholder="Contoh: Kandang A1" required>
            </div>
            <div class="form-group">
                <label>Jenis Ternak</label>
                <select name="jenis_ternak" required>
                    <option value="Sapi">Sapi</option>
                    <option value="Kambing">Kambing</option>
                    <option value="Domba">Domba</option>
                    <option value="Unggas">Unggas</option>
                </select>
            </div>
            <div class="form-group">
                <label>Populasi (Ekor)</label>
                <input type="number" name="populasi" placeholder="0" required min="1">
            </div>
            <button type="submit" name="submit_sumber" class="btn-save">SIMPAN DATA</button>
        </form>
    </div>
</div>

<script>
    function bukaModal() { document.getElementById("modalTambah").style.display = "block"; }
    function tutupModal() { document.getElementById("modalTambah").style.display = "none"; }
    
    window.onclick = function(event) {
        let modal = document.getElementById("modalTambah");
        if (event.target == modal) modal.style.display = "none";
    }
</script>

</body>
</html>