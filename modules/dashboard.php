<?php
// Total Jenis Barang
$total_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];

// Total Stok Masuk
$stok_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM stock_transactions WHERE type = 'masuk'"))['total'] ?? 0;

// Total Stok Keluar
$stok_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM stock_transactions WHERE type = 'keluar'"))['total'] ?? 0;

// Stock Terendah < 10
$stok_rendah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE stock < 10"))['total'];

// Stock Tertinggi
$stok_tertinggi_res = mysqli_query($conn, "SELECT name, stock FROM products ORDER BY stock DESC LIMIT 1");
$stok_tertinggi = mysqli_fetch_assoc($stok_tertinggi_res);
$nama_tertinggi = $stok_tertinggi['name'] ?? '-';
$jumlah_tertinggi = $stok_tertinggi['stock'] ?? 0;
?>

<div class="row">
    <div class="col-md-12 mb-4">
        <h3>Dashboard</h3>
        <p class="text-muted">Ringkasan statistik persediaan barang Toko JeWePe (Skema Baru).</p>
    </div>
</div>

<div class="row">
    <!-- Total Barang -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jenis Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_barang ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Masuk -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-success border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Stok Masuk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stok_masuk ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-arrow-down-left-circle fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Keluar -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-danger border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Stok Keluar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stok_keluar ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-arrow-up-right-circle fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Rendah -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-warning border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stok Rendah (< 10)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stok_rendah ?> Barang</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-triangle fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">Stok Tertinggi Saat Ini</h6>
            </div>
            <div class="card-body text-center py-4">
                <h2 class="display-4 text-primary"><?= $jumlah_tertinggi ?></h2>
                <p class="lead mb-0"><?= $nama_tertinggi ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h6 class="m-0 font-weight-bold">Info Admin</h6>
            </div>
            <div class="card-body">
                <p>Nama: <strong><?= $_SESSION['nama'] ?></strong></p>
                <p>Status: <span class="badge bg-success">Online</span></p>
                <p>Waktu Server: <?= date('d M Y, H:i') ?></p>
            </div>
        </div>
    </div>
</div>
