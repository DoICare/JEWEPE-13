<?php
// Handle Add Transaction
if (isset($_POST['add_transaction'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $transaction_date = $_POST['transaction_date'];
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    // Get current stock
    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock FROM products WHERE id = '$product_id'"));
    $current_stock = $p['stock'];

    // Validation for 'keluar'
    if ($type == 'keluar' && $current_stock < $quantity) {
        echo "<script>alert('Stok tidak mencukupi!'); window.location='index.php?page=stok';</script>";
        exit;
    }

    // Calculate stock after
    $stock_after = ($type == 'masuk') ? ($current_stock + $quantity) : ($current_stock - $quantity);

    // Start Transaction
    mysqli_begin_transaction($conn);
    try {
        // 1. Insert into stock_transactions
        mysqli_query($conn, "INSERT INTO stock_transactions (product_id, user_id, type, quantity, stock_after, transaction_date, note) 
                            VALUES ('$product_id', '$user_id', '$type', '$quantity', '$stock_after', '$transaction_date', '$note')");

        // 2. Update products stock
        mysqli_query($conn, "UPDATE products SET stock = '$stock_after' WHERE id = '$product_id'");

        mysqli_commit($conn);
        echo "<script>alert('Transaksi stok berhasil!'); window.location='index.php?page=stok';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal: " . $e->getMessage() . "');</script>";
    }
}

$products_res = mysqli_query($conn, "SELECT id, name, code, stock, unit FROM products ORDER BY name ASC");
$products_options = [];
while($p = mysqli_fetch_assoc($products_res)) $products_options[] = $p;

$transactions_list = mysqli_query($conn, "SELECT t.*, p.name as product_name, p.code as product_code, u.name as user_name 
                                         FROM stock_transactions t 
                                         JOIN products p ON t.product_id = p.id 
                                         JOIN users u ON t.user_id = u.id 
                                         ORDER BY t.id DESC LIMIT 50");
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4>Persediaan Barang (Skema Baru)</h4>
    </div>
</div>

<div class="row">
    <!-- Form Transaksi -->
    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0">Form Transaksi Stok</h6>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($products_options as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['name'] ?> (Stok: <?= $p['stock'] ?> <?= $p['unit'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi</label>
                        <select name="type" class="form-select" required>
                            <option value="masuk">Barang Masuk</option>
                            <option value="keluar">Barang Keluar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Opsional"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="add_transaction" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h6 class="m-0">50 Transaksi Terakhir</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Stok Akhir</th>
                                <th>Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($t = mysqli_fetch_assoc($transactions_list)): ?>
                            <tr>
                                <td><?= date('d/m/y', strtotime($t['transaction_date'])) ?></td>
                                <td><?= $t['product_name'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $t['type'] == 'masuk' ? 'success' : 'danger' ?>">
                                        <?= strtoupper($t['type']) ?>
                                    </span>
                                </td>
                                <td><?= $t['quantity'] ?></td>
                                <td><?= $t['stock_after'] ?></td>
                                <td><small><?= $t['user_name'] ?></small></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
