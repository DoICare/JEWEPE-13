<?php
// Handle Add Product
if (isset($_POST['add_product'])) {
    $category_id = $_POST['category_id'];
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $minimum_stock = $_POST['minimum_stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $stock = 0; // Initial stock always 0

    $query = "INSERT INTO products (category_id, code, name, unit, stock, minimum_stock, description) 
              VALUES ('$category_id', '$code', '$name', '$unit', '$stock', '$minimum_stock', '$description')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang berhasil ditambah!'); window.location='index.php?page=barang';</script>";
    }
}

// Handle Edit Product
if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $minimum_stock = $_POST['minimum_stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "UPDATE products SET category_id = '$category_id', code = '$code', 
              name = '$name', unit = '$unit', minimum_stock = '$minimum_stock', 
              description = '$description' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang berhasil diupdate!'); window.location='index.php?page=barang';</script>";
    }
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
    echo "<script>alert('Barang berhasil dihapus!'); window.location='index.php?page=barang';</script>";
}

$products_list = mysqli_query($conn, "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$categories_res = mysqli_query($conn, "SELECT * FROM categories");
$categories_options = [];
while($c = mysqli_fetch_assoc($categories_res)) $categories_options[] = $c;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Master Data Barang (Skema Baru)</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Tambah Barang
    </button>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Min. Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($products_list)): ?>
                    <tr>
                        <td><code><?= $row['code'] ?></code></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['category_name'] ?></td>
                        <td><?= $row['unit'] ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td><?= $row['minimum_stock'] ?></td>
                        <td>
                            <?php if($row['stock'] <= 0): ?>
                                <span class="badge bg-danger">Habis</span>
                            <?php elseif($row['stock'] < $row['minimum_stock']): ?>
                                <span class="badge bg-warning text-dark">Hampir Habis</span>
                            <?php else: ?>
                                <span class="badge bg-success">Tersedia</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id'] ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="index.php?page=barang&delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Detail Modal -->
                    <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-borderless">
                                        <tr><th width="35%">Kode Barang</th><td>: <?= $row['code'] ?></td></tr>
                                        <tr><th>Nama Barang</th><td>: <?= $row['name'] ?></td></tr>
                                        <tr><th>Kategori</th><td>: <?= $row['category_name'] ?></td></tr>
                                        <tr><th>Satuan</th><td>: <?= $row['unit'] ?></td></tr>
                                        <tr><th>Stok Saat Ini</th><td>: <strong><?= $row['stock'] ?></strong></td></tr>
                                        <tr><th>Batas Minimum</th><td>: <?= $row['minimum_stock'] ?></td></tr>
                                        <tr><th>Deskripsi</th><td>: <?= $row['description'] ?: '-' ?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="" method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select name="category_id" class="form-select" required>
                                            <?php foreach($categories_options as $c): ?>
                                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $row['category_id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Barang</label>
                                        <input type="text" name="code" class="form-control" value="<?= $row['code'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Barang</label>
                                        <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="unit" class="form-control" value="<?= $row['unit'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stok Minimum</label>
                                        <input type="number" name="minimum_stock" class="form-control" value="<?= $row['minimum_stock'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="description" class="form-control" rows="3"><?= $row['description'] ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="edit_product" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($categories_options as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Barang</label>
                    <input type="text" name="code" class="form-control" placeholder="Contoh: SMN-001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" class="form-control" placeholder="Sak / Pcs / Batang" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number" name="minimum_stock" class="form-control" value="10" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="add_product" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
