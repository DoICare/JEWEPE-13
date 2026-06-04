<?php
$tgl_mulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
$tgl_selesai = $_GET['tgl_selesai'] ?? date('Y-m-d');

$query = "SELECT t.*, p.name as product_name, p.code as product_code, c.name as category_name, u.name as user_name 
          FROM stock_transactions t 
          JOIN products p ON t.product_id = p.id 
          LEFT JOIN categories c ON p.category_id = c.id
          JOIN users u ON t.user_id = u.id
          WHERE t.transaction_date BETWEEN '$tgl_mulai' AND '$tgl_selesai'
          ORDER BY t.transaction_date DESC, t.id DESC";

$laporan_list = mysqli_query($conn, $query);

// Summary for filtered report
$summary_in = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM stock_transactions WHERE type = 'masuk' AND transaction_date BETWEEN '$tgl_mulai' AND '$tgl_selesai'"))['total'] ?? 0;
$summary_out = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM stock_transactions WHERE type = 'keluar' AND transaction_date BETWEEN '$tgl_mulai' AND '$tgl_selesai'"))['total'] ?? 0;
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4>Laporan Keluar Masuk Barang (Skema Baru)</h4>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="laporan">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Filter Laporan
                </button>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-success w-100" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <span>Total Barang Masuk (Periode):</span>
            <strong><?= $summary_in ?></strong>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-danger d-flex justify-content-between align-items-center">
            <span>Total Barang Keluar (Periode):</span>
            <strong><?= $summary_out ?></strong>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Stok Akhir</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($laporan_list) > 0):
                        while($row = mysqli_fetch_assoc($laporan_list)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($row['transaction_date'])) ?></td>
                        <td><code><?= $row['product_code'] ?></code></td>
                        <td><?= $row['product_name'] ?></td>
                        <td>
                            <span class="badge bg-<?= $row['type'] == 'masuk' ? 'success' : 'danger' ?>">
                                <?= strtoupper($row['type']) ?>
                            </span>
                        </td>
                        <td class="text-end"><?= number_format($row['quantity']) ?></td>
                        <td class="text-end"><?= number_format($row['stock_after']) ?></td>
                        <td><?= $row['user_name'] ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data transaksi pada periode ini.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    #sidebar, .navbar, .btn, .card-body form, .mt-5, .alert {
        display: none !important;
    }
    #content {
        width: 100% !important;
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
}
</style>
