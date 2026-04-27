<?php
include 'koneksi.php';

//jika tidak login maka tidak bisa masuk ke form ini
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit;
}

// 1. Pastikan variabel didefinisikan sebagai array kosong agar tidak error jika DB kosong
$data_hewan = [];

// 2. LOGIKA TAMBAH DATA (Simpan ke Database)
if (isset($_POST['submit_tambah'])) {
    try {
        $sql = "INSERT INTO tb_hewan (id_hewan, jenis_hewan, usia, berat_badan, status_kesehatan) 
                VALUES (:id, :jenis, :usia, :bb, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id'     => $_POST['id'],
            ':jenis'  => $_POST['jenis'],
            ':usia'   => $_POST['usia'],
            ':bb'     => $_POST['bb'],
            ':status' => $_POST['status']
        ]);
        header("Location: datahewan.php");
        exit();
    } catch(PDOException $e) {
        echo "<script>alert('Gagal tambah: " . $e->getMessage() . "');</script>";
    }
}

// 3. LOGIKA HAPUS DATA
if (isset($_GET['hapus'])) {
    $sql = "DELETE FROM tb_hewan WHERE id_hewan = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $_GET['hapus']]);
    header("Location: datahewan.php");
    exit();
}

// 4. AMBIL DATA DARI DATABASE (Mengisi variabel $data_hewan)
try {
    $query = "SELECT * FROM tb_hewan ORDER BY id_hewan ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data_hewan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log($e->getMessage());
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

            <section class="table-section">
                <div class="action-bar">
                    <div class="search-wrapper">
                        <input type="text" placeholder="Search by ID, type, or status...">
                    </div>
                    <a href="tambah.php" class="btn-add" style="text-decoration: none;"><i class="fas fa-plus"></i> TAMBAH</a>
                </div>

                <div class="table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>HEWAN ID</th>
                                <th>JENIS HEWAN</th>
                                <th>USIA</th>
                                <th>BB</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data_hewan as $index => $hwn): ?>
                                <tr class="<?php echo ($index % 2 != 0) ? 'bg-mint' : ''; ?>">
                                    <td><span class="id-badge" style="background:#eee; padding:4px 8px; border-radius:4px;"><?php echo htmlspecialchars($hwn['id_hewan']); ?></span></td>
                                    <td><?php echo htmlspecialchars($hwn['jenis_hewan']); ?></td>
                                    <td><?php echo htmlspecialchars($hwn['usia']); ?></td>
                                    <td><?php echo htmlspecialchars($hwn['berat_badan']); ?></td>
                                    <td><span class="status-pill" style="color:#22C55E; font-weight:bold;">• <?php echo htmlspecialchars($hwn['status_kesehatan']); ?></span></td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="edit.php?id=<?php echo $hwn['id_hewan']; ?>" class="btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="hapus.php?id=<?php echo $hwn['id_hewan']; ?>" 
                                            class="btn-delete" 
                                            onclick="return confirm('Yakin hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>