<nav id="sidebar">
    <div class="sidebar-header">
        <h5>JeWePe Inventory</h5>
    </div>
    <ul class="list-unstyled components">
        <li class="<?= (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : '' ?>">
            <a href="index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        </li>
                <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'stok') ? 'active' : '' ?>">
            <a href="index.php?page=stok"><i class="bi bi-box-seam"></i> Persediaan Barang</a>
        </li>
        <li>
            <a href="#masterSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-database"></i> Master Data
            </a>
            <ul class="collapse list-unstyled <?= (isset($_GET['page']) && in_array($_GET['page'], ['kategori', 'barang', 'users'])) ? 'show' : '' ?>" id="masterSubmenu">
                <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'kategori') ? 'active' : '' ?>">
                    <a href="index.php?page=kategori">Kategori Barang</a>
                </li>
                <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'barang') ? 'active' : '' ?>">
                    <a href="index.php?page=barang">Daftar Barang</a>
                </li>
                <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'users') ? 'active' : '' ?>">
                    <a href="index.php?page=users">Manajemen Pengguna</a>
                </li>
            </ul>
        </li>
        <li class="<?= (isset($_GET['page']) && $_GET['page'] == 'laporan') ? 'active' : '' ?>">
            <a href="index.php?page=laporan"><i class="bi bi-file-earmark-text"></i> Laporan</a>
        </li>
        <li class="mt-5">
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
    </ul>
</nav>

<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-primary d-md-none">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">Selamat Datang, <strong><?= $_SESSION['nama'] ?></strong></span>
                <i class="bi bi-person-circle fs-4"></i>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
