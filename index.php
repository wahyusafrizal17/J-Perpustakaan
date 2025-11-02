<?php
error_reporting(0);
session_start();

$koneksi = new mysqli("localhost","root","WahyuJR17_","db_perpustakaan");
include "function.php";

if($_SESSION['admin'] || $_SESSION['user']) {
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PERPUSTAKAAN</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/sidebar.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        #page-wrapper {
            margin-left: 250px;
            background-color: #f5f7fb;
            min-height: 100vh;
            padding: 20px;
        }
    </style>
</head>

<body>
<div id="wrapper">
    <!-- Nabar -->
    <nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0; background-color: #fff; border: none;">

    <div style="display: flex; align-items: center; gap: 15px; padding: 15px 50px 5px 50px; float: right;">
        <span style="color: #000; font-size: 16px;"><?php echo date('d-M-Y'); ?></span>
    </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2 class="logo" style="font-weight: bolder;">SIPA</h2>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="<?php echo ($page == '' ? 'active' : ''); ?>">
                <i class="fa fa-dashboard"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Data Master</span>
                <i class="fa fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu" <?php echo in_array($page, ['lokasi','buku','anggota','presensi']) ? 'style="display:block;"' : ''; ?>>
                <?php if ($_SESSION['admin']) { ?>
                    <li><a href="?page=lokasi" class="<?php echo ($page == 'lokasi' ? 'active' : ''); ?>">Data Lokasi Buku</a></li>
                    <li><a href="?page=buku" class="<?php echo ($page == 'buku' ? 'active' : ''); ?>">Data Buku</a></li>
                    <li><a href="?page=anggota" class="<?php echo ($page == 'anggota' ? 'active' : ''); ?>">Data Anggota</a></li>
                    <li><a href="?page=presensi" class="<?php echo ($page == 'presensi' ? 'active' : ''); ?>">Presensi</a></li>
                <?php } else { ?>
                    <li><a href="?page=lokasi" class="<?php echo ($page == 'lokasi' ? 'active' : ''); ?>">Data Lokasi Buku</a></li>
                    <li><a href="?page=buku" class="<?php echo ($page == 'buku' ? 'active' : ''); ?>">Data Buku</a></li>
                    <li><a href="?page=presensi" class="<?php echo ($page == 'presensi' ? 'active' : ''); ?>">Presensi</a></li>
                <?php } ?>
                </ul>
            </li>

            <li>
                <a href="?page=transaksi" class="<?php echo ($page == 'transaksi' ? 'active' : ''); ?>">
                <i class="fa fa-edit"></i><span>Data Transaksi</span>
                </a>
            </li>

            <?php if ($_SESSION['admin']) { ?> 
                <li>
                <a href="?page=pengguna" class="<?php echo ($page == 'pengguna' ? 'active' : ''); ?>">
                    <i class="fa fa-user"></i><span>Data Pengguna</span>
                </a>
                </li>

                <li class="menu-item">
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    <span>Laporan</span>
                    <i class="fa fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu" <?php echo ($page == 'laporan' ? 'style="display:block;"' : ''); ?>>
                    <li><a href="?page=buku&aksi=cetak">Laporan Buku</a></li>
                    <li><a href="?page=anggota&aksi=cetak">Laporan Anggota</a></li>
                    <li><a href="?page=presensi&aksi=cetak">Laporan Presensi</a></li>
                    <li><a href="?page=transaksi&aksi=cetak">Laporan Transaksi</a></li>
                </ul>
                </li>
            <?php } ?>
        </ul>

        <li>
            <a href="logout.php" class="logout-btn">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </li>

    </aside>



    <!-- Halaman Konten -->
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">

                <?php
                $page = $_GET['page'];
                $aksi = $_GET['aksi'];

                // Routing halaman tetap sama seperti sebelumnya
                if ($page == "buku") {
                    if ($aksi == "") include "page/buku/buku.php";
                    elseif ($aksi== "tambah") include "page/buku/tambah.php";
                    elseif ($aksi== "ubah") include "page/buku/ubah.php";
                    elseif ($aksi== "hapus") include "page/buku/hapus.php";
                    elseif ($aksi== "cetak") include "page/buku/form_laporan_buku.php";
                    elseif ($aksi== "import") include "page/buku/import.php";
                } elseif ($page == "lokasi") {
                    if ($aksi == "") include "page/lokasi/lokasi.php";
                    elseif ($aksi == "tambah") include "page/lokasi/tambah.php";
                    elseif ($aksi == "ubah") include "page/lokasi/ubah.php";
                    elseif ($aksi == "hapus") include "page/lokasi/hapus.php";
                } elseif ($page == "anggota") {
                    if ($aksi == "") include "page/anggota/anggota.php";
                    elseif ($aksi == "tambah") include "page/anggota/tambah.php";
                    elseif ($aksi == "ubah") include "page/anggota/ubah.php";
                    elseif ($aksi == "hapus") include "page/anggota/hapus.php";
                    elseif ($aksi== "cetak") include "page/anggota/form_laporan_anggota.php";
                    elseif ($aksi== "import") include "page/anggota/import.php";
                } elseif ($page == "presensi") {
                    if ($aksi == "") include "page/presensi/presensi.php";
                    elseif ($aksi == "absen") include "page/presensi/presensi.php";
                    elseif ($aksi == "tambah") include "page/presensi/tambah.php";
                    elseif ($aksi == "hapus") include "page/presensi/hapus.php";
                    elseif ($aksi== "cetak") include "page/presensi/form_laporan_presensi.php";
                } elseif ($page == "transaksi") {
                    if ($aksi == "") include "page/transaksi/transaksi.php";
                    elseif ($aksi == "tambah") include "page/transaksi/tambah.php";
                    elseif ($aksi == "kembali") include "page/transaksi/kembali.php";
                    elseif ($aksi == "perpanjang") include "page/transaksi/perpanjang.php";
                    elseif ($aksi== "cetak") include "page/transaksi/form_laporan_transaksi.php";
                } elseif ($page == "pengguna") {
                    if ($aksi == "") include "page/pengguna/pengguna.php";
                    elseif ($aksi == "tambah") include "page/pengguna/tambah.php";
                    elseif ($aksi == "ubah") include "page/pengguna/ubah.php";
                    elseif ($aksi == "hapus") include "page/pengguna/hapus.php";
                } elseif ($page == "") {
                    include "home.php";
                }
                ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.metisMenu.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function () {
    $('#dataTables-example').dataTable();
    $('.select2').select2();
});
</script>
<script src="assets/js/custom.js"></script>
<script>
    $(document).ready(function() {
    // Toggle submenu hanya saat diklik
    $('.menu-item > a').click(function(e) {
        e.preventDefault();
        const submenu = $(this).next('.submenu');
        
        // Tutup submenu lain biar rapi
        $('.submenu').not(submenu).slideUp();
        $('.dropdown-icon').not($(this).find('.dropdown-icon')).removeClass('rotated');
        
        // Buka submenu yang diklik
        submenu.slideToggle();
        $(this).find('.dropdown-icon').toggleClass('rotated');
    });
    });
</script>

</body>
</html>

<?php
} else {
    header("location:login.php");
}
?>
