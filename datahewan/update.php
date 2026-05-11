<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login/index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM tb_hewan WHERE id_hewan = :id");
$stmt->execute([':id' => $id]);
$hewan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hewan) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['submit_edit'])) {
    try {
        $sql = "UPDATE tb_hewan SET 
                jenis_hewan     = :jenis, 
                usia           = :usia, 
                berat_badan     = :bb, 
                status_kesehatan = :status 
                WHERE id_hewan  = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':jenis'  => $_POST['jenis'],
            ':usia'   => $_POST['usia'],
            ':bb'     => $_POST['bb'],
            ':status' => $_POST['status'],
            ':id'     => $id
        ]);

        header("Location: index.php?pesan=berhasil_edit");
        exit(); 

    } catch(PDOException $e) {
        echo "<script>alert('Gagal update: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Hewan - ModernFarm</title>
    <link rel="stylesheet" href="globals.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_datahewan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tambahan style khusus untuk form edit agar rapi */
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 20px auto;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn-container { display: flex; gap: 10px; margin-top: 30px; }
        .btn-save { background: #22C55E; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; flex: 2; }
        .btn-cancel { background: #64748b; color: white; text-decoration: none; padding: 12px 25px; border-radius: 8px; text-align: center; flex: 1; }
        .btn-save:hover { background: #16a34a; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar yang sama dengan index -->
        <aside class="sidebar">
            <div class="logo">
                <img src="asset/daun.jpg" alt="Logo"> Modern<span>Farm</span>
            </div>
            <nav class="menu">
                <a href="../dasboard/index.php" class="menu-item"><img src="asset/iconehome.jpg" alt=""> Dashboard</a>
                <a href="index.php" class="menu-item active"><img src="asset/datahewan.jpg" alt=""> Data Hewan</a>
                <a href="../jenislimbah/index.php" class="menu-item"><img src="asset/datapakan.jpg" alt=""> Limbah</a>
                <a href="logout.php" class="menu-item"><img src="asset/logout.jpg" alt=""> Logout</a>
            </nav>
        </aside>

        <main class="content">
            <header class="header">
                <h2>Edit Data Hewan</h2>
                <div class="user-info">
                    <strong>Amin</strong>
                </div>
            </header>

            <section class="form-section">
                <div class="form-card">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>ID Hewan (Tidak bisa diubah)</label>
                            <input type="text" value="<?php echo $hewan['id_hewan']; ?>" disabled style="background: #f1f5f9;">
                        </div>

                        <div class="form-group">
                            <label>Jenis Hewan</label>
                            <input type="text" name="jenis" value="<?php echo htmlspecialchars($hewan['jenis_hewan']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Usia (Bulan/Tahun)</label>
                            <input type="text" name="usia" value="<?php echo htmlspecialchars($hewan['usia']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="bb" value="<?php echo htmlspecialchars($hewan['berat_badan']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Status Kesehatan</label>
                            <select name="status" required>
                                <option value="Sehat" <?php echo ($hewan['status_kesehatan'] == 'Sehat') ? 'selected' : ''; ?>>Sehat</option>
                                <option value="Sakit" <?php echo ($hewan['status_kesehatan'] == 'Sakit') ? 'selected' : ''; ?>>Sakit</option>
                                <option value="Karantina" <?php echo ($hewan['status_kesehatan'] == 'Karantina') ? 'selected' : ''; ?>>Karantina</option>
                            </select>
                        </div>

                        <div class="btn-container">
                            <a href="index.php" class="btn-cancel">Batal</a>
                            <button type="submit" name="submit_edit" class="btn-save">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>