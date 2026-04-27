<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit;
}
// Data dummy pakan
$pakan_items = [
    ["nama" => "Jerami", "stok" => 1400, "max" => 2000, "warna" => "#22C55E"],
    ["nama" => "Grain Mix", "stok" => 980, "max" => 1500, "warna" => "#00B894"],
    ["nama" => "Hijauan fermentasi", "stok" => 860, "max" => 1200, "warna" => "#00CEC9"],
    ["nama" => "Berkonsentrasi", "stok" => 600, "max" => 1000, "warna" => "#A2EE00"]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModernFarm Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="asset/daun.jpg" alt=""> Modern<span>Farm</span>
            </div>
            <nav class="menu">
                <a href="#" class="menu-item active">
                    <img src="asset/iconehome.jpg" alt=""> Dashboard
                </a>
                <a href="/peternakan/datahewan/datahewan.php" class="menu-item">
                    <img src="asset/datahewan.jpg" alt=""> Data Hewan
                </a>
                <a href="/peternakan/jenislimbah/limbahternak.php" class="menu-item">
                    <img src="asset/datapakan.jpg" alt=""> Data Pakan
                </a>
                <a href="#" class="menu-item">
                    <img src="asset/laporan.jpg" alt=""> Laporan
                </a>
                <a href="#" class="menu-item">
                    <img src="asset/pengaturan.jpg" alt=""> Pengaturan
                </a>
                <a href="#" class="menu-item">
                    <img src="asset/logout.jpg" alt=""> Logout
                </a>
            </nav>
        </aside>

        <main class="content">
            <header class="header">
                <div class="user-info">
                    <div class="user-text">
                        <span>User</span>
                        <strong>Amin</strong>
                    </div>
                    <div class="avatar">
                        <img src="asset/profil.jpg" alt="">
                    </div>
                </div>
            </header>

            <div class="dashboard-grid">
                <div class="card">
                    <h3>🌾 Tingkat Persediaan Saat Ini</h3>
                    <div class="stok-container">
                        <?php foreach($pakan_items as $item): 
                            $persen = ($item['stok'] / $item['max']) * 100;
                        ?>
                        <div class="stok-row">
                            <div class="stok-info">
                                <span><?php echo $item['nama']; ?></span>
                                <span><strong><?php echo number_format($item['stok'],0,',','.'); ?> kg</strong> / <?php echo number_format($item['max'],0,',','.'); ?> kg</span>
                            </div>
                            <div class="progress-bg">
                                <div class="progress-fill" style="width: <?php echo $persen; ?>%; background: <?php echo $item['warna']; ?>;"></div>
                            </div>
                            <div class="remaining"><?php echo round($persen); ?>% remaining</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="total-available">
                        Total Available <span>3,840 kg</span>
                    </div>
                </div>

                <div class="card">
                    <h3>📊 Distribusi Pakan</h3>
                    <div class="chart-box">
                        <img src="asset/distribusipakan.jpg" alt="Chart" class="img-fluid">
                    </div>
                </div>

                <div class="card">
                    <h3>📅 Penggunaan & Pengisian Kembali Pakan Mingguan</h3>
                    <div class="chart-box">
                        <img src="asset/barchart.jpg" alt="Chart" class="img-fluid">
                    </div>
                </div>

                <div class="card">
                    <h3>📈 Stock Level Trend (Last 7 Days)</h3>
                    <div class="chart-box">
                        <img src="asset/AreaChart.jpg" alt="Chart" class="img-fluid">
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>