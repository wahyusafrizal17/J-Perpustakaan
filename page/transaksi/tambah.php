<?php
$pinjam = date("d-m-Y");
$kembali = date("d-m-Y", strtotime("+7 days"));

// Jika login sebagai user (bukan admin), cari NIS anggota berdasarkan user yang login
$selected_nis = '';
if (isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    // Ambil data user yang sedang login
    $user_id = $_SESSION['user'];
    $user_query = $koneksi->query("SELECT * FROM tb_user WHERE id = '$user_id'");
    
    if ($user_query && $user_query->num_rows > 0) {
        $user_data = $user_query->fetch_assoc();
        $username = $user_data['username'];
        $nama_user = isset($user_data['nama']) ? $user_data['nama'] : '';
        
        // Cari anggota berdasarkan nama atau username (case insensitive)
        if (!empty($nama_user) || !empty($username)) {
            $nama_user_escaped = $koneksi->real_escape_string($nama_user);
            $username_escaped = $koneksi->real_escape_string($username);
            
            $sql_anggota = "SELECT * FROM tb_anggota WHERE LOWER(nama) LIKE LOWER('%$nama_user_escaped%') OR LOWER(nama) LIKE LOWER('%$username_escaped%') LIMIT 1";
            $anggota_query = $koneksi->query($sql_anggota);
            
            if ($anggota_query !== false && $anggota_query->num_rows > 0) {
                $anggota_data = $anggota_query->fetch_assoc();
                if ($anggota_data && isset($anggota_data['nis']) && !empty($anggota_data['nis'])) {
                    $selected_nis = $anggota_data['nis'];
                }
            }
        }
    }
}
?>

<div class="panel panel-default">
    <div class="panel-heading">Tambah Data Transaksi</div>
    <div class="panel-body">
        <form method="POST" action="">
            <div class="form-group">
                <label>Judul Buku</label>
                <select class="form-control" name="id_buku" required>
                    <option value="">== Pilih ==</option>
                    <?php
                    $query = $koneksi->query("SELECT * FROM tb_buku ORDER BY id_buku");
                    while ($buku = $query->fetch_assoc()) {
                        echo "<option value='{$buku['id_buku']}'>{$buku['judul']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Anggota</label>
                <select class="form-control" name="nis" required <?php echo (isset($_SESSION['user']) && !isset($_SESSION['admin']) && !empty($selected_nis)) ? 'disabled' : ''; ?>>
                    <option value="">== Pilih ==</option>
                    <?php
                    $query = $koneksi->query("SELECT * FROM tb_anggota ORDER BY nis");
                    while ($anggota = $query->fetch_assoc()) {
                        $selected = ($anggota['nis'] == $selected_nis) ? 'selected' : '';
                        echo "<option value='{$anggota['nis']}' $selected>{$anggota['nis']} - {$anggota['nama']}</option>";
                    }
                    ?>
                </select>
                <?php if (isset($_SESSION['user']) && !isset($_SESSION['admin']) && !empty($selected_nis)) { ?>
                <input type="hidden" name="nis" value="<?php echo $selected_nis; ?>">
                <?php } ?>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input class="form-control" type="text" name="pinjam" value="<?= $pinjam ?>" />
            </div>

            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input class="form-control" type="text" name="kembali" value="<?= $kembali ?>" />
            </div>

            <div>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-warning">Reset</button>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $id_buku = $_POST['id_buku'];
    $nis = $_POST['nis'];
    $tgl_pinjam = $_POST['pinjam'];
    $tgl_kembali = $_POST['kembali'];
    $tgl_input = date("Y-m-d H:i:s"); // format datetime MySQL

    // Ambil data buku
    $queryBuku = $koneksi->query("SELECT * FROM tb_buku WHERE id_buku = '$id_buku'");
    $buku = $queryBuku->fetch_assoc();

    if (!$buku) {
        echo "<script>alert('Buku tidak ditemukan!');</script>";
        exit;
    }

    // Cek stok buku
    if ($buku['jumlah_buku'] <= 0) {
        echo "<script>alert('Stok buku habis! Tambahkan stok terlebih dahulu');</script>";
        echo "<meta http-equiv='refresh' content='0; url=?page=transaksi&aksi=tambah'>";
        exit;
    }

    // Ambil data anggota
    $queryAnggota = $koneksi->query("SELECT * FROM tb_anggota WHERE nis = '$nis'");
    $anggota = $queryAnggota->fetch_assoc();

    if (!$anggota) {
        echo "<script>alert('Anggota tidak ditemukan!');</script>";
        exit;
    }

    $nama = $anggota['nama'];
    $judul = $buku['judul'];

    // Cek apakah anggota sudah pinjam buku yang sama dan belum dikembalikan
    $cek = $koneksi->query("SELECT * FROM tb_transaksi WHERE nis = '$nis' AND id_buku = '$id_buku' AND status = 'Pinjam'");
    if ($cek->num_rows > 0) {
        echo "<script>alert('Anggota ini sudah meminjam buku yang sama!');</script>";
        echo "<meta http-equiv='refresh' content='0; url=?page=transaksi&aksi=tambah'>";
        exit;
    }

    // Simpan transaksi
    $sql = "INSERT INTO tb_transaksi (id_buku, judul, nis, nama, tgl_pinjam, tgl_kembali, status, tgl_input)
            VALUES ('$id_buku', '$judul', '$nis', '$nama', '$tgl_pinjam', '$tgl_kembali', 'Pinjam', '$tgl_input')";

    $simpan = $koneksi->query($sql);

    if ($simpan) {
        // Kurangi stok buku
        $koneksi->query("UPDATE tb_buku SET jumlah_buku = jumlah_buku - 1 WHERE id_buku = '$id_buku'");
        echo "<script>alert('Transaksi berhasil disimpan!');</script>";
        echo "<meta http-equiv='refresh' content='0; url=?page=transaksi'>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi!');</script>";
    }
}
?>
