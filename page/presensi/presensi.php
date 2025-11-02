<?php
// Start output buffering untuk memastikan redirect bisa dilakukan
if (!ob_get_level()) {
    ob_start();
}

// Cek apakah user adalah admin atau user biasa
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'];

// Jika user biasa, tampilkan tampilan khusus
if (!$is_admin && isset($_SESSION['user'])) {
    // Ambil data user yang sedang login
    $user_id = $_SESSION['user'];
    $user_query = $koneksi->query("SELECT * FROM tb_user WHERE id = '$user_id'");
    
    if (!$user_query || $user_query->num_rows == 0) {
        // Jika user tidak ditemukan, redirect atau tampilkan error
        echo '<div class="alert alert-danger">User tidak ditemukan. Silakan login ulang.</div>';
        exit;
    }
    
    $user_data = $user_query->fetch_assoc();
    
    // Ambil data user yang login
    $username = $user_data['username'];
    $nama_user = $user_data['nama'];
    $user_id = $user_data['id'];
    
    // Inisialisasi variabel default terlebih dahulu
    $anggota_data = null;
    $nis = 'U' . $user_id; // Default: gunakan user_id sebagai NIS
    $nama_anggota = !empty($nama_user) ? $nama_user : $username; // Default dari nama user atau username
    $kelas = '-'; // Default kelas jika tidak ditemukan
    
    // Cari anggota berdasarkan nama atau username (case insensitive)
    // Escape input untuk keamanan
    if (!empty($nama_user) || !empty($username)) {
        $nama_user_escaped = $koneksi->real_escape_string($nama_user);
        $username_escaped = $koneksi->real_escape_string($username);
        
        $sql_anggota = "SELECT * FROM tb_anggota WHERE LOWER(nama) LIKE LOWER('%$nama_user_escaped%') OR LOWER(nama) LIKE LOWER('%$username_escaped%') LIMIT 1";
        $anggota_query = $koneksi->query($sql_anggota);
        
        // Cek apakah query berhasil dan ada hasil
        if ($anggota_query !== false && $anggota_query->num_rows > 0) {
            // Jika ditemukan di tb_anggota, gunakan data dari sana
            $anggota_data = $anggota_query->fetch_assoc();
            if ($anggota_data && isset($anggota_data['nis']) && !empty($anggota_data['nis'])) {
                $nis = $anggota_data['nis'];
                $nama_anggota = isset($anggota_data['nama']) ? $anggota_data['nama'] : $nama_anggota;
                $kelas = isset($anggota_data['kelas']) && !empty($anggota_data['kelas']) ? $anggota_data['kelas'] : '-';
            }
        }
    }
    
    // Pastikan semua variabel memiliki nilai yang valid
    $nis = (string)$nis;
    $nama_anggota = (string)$nama_anggota;
    $kelas = (string)$kelas;
    
    // Proses auto presensi jika ada aksi=absen (harus dijalankan sebelum output HTML)
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'absen') {
        // Pastikan semua variabel sudah terdefinisi dengan nilai default
        if (empty($nis)) {
            $nis = 'U' . $user_id;
        }
        if (empty($nama_anggota)) {
            $nama_anggota = !empty($nama_user) ? $nama_user : $username;
        }
        if (empty($kelas)) {
            $kelas = '-';
        }
        
        // Pastikan variabel tidak null
        $nis = (string)$nis;
        $nama_anggota = (string)$nama_anggota;
        $kelas = (string)$kelas;
        
        // Escape untuk keamanan (gunakan variabel terpisah untuk query)
        $nis_escaped = $koneksi->real_escape_string($nis);
        $nama_anggota_escaped = $koneksi->real_escape_string($nama_anggota);
        $kelas_escaped = $koneksi->real_escape_string($kelas);
        
        // Cek apakah sudah presensi hari ini berdasarkan nama_anggota atau NIS
        $today = date('Y-m-d');
        $sql_cek = "SELECT * FROM tb_presensi WHERE (nis = '$nis_escaped' OR nama_anggota = '$nama_anggota_escaped') AND DATE(tgl_presensi) = '$today'";
        $cek_presensi = $koneksi->query($sql_cek);
        
        // Jika query error, handle dengan benar
        if ($cek_presensi === false) {
            // Bersihkan semua output buffer
            while (ob_get_level()) {
                ob_end_clean();
            }
            // Coba header redirect, jika gagal gunakan JavaScript
            if (!headers_sent()) {
                header("Location: index.php?page=presensi&status=failed");
                exit();
            } else {
                echo '<script>window.location.href="index.php?page=presensi&status=failed";</script>';
                exit();
            }
        }
        
        if ($cek_presensi->num_rows == 0) {
            // Insert presensi dengan data user yang login
            $sql_insert = "INSERT INTO tb_presensi (nis, nama_anggota, kelas) VALUES ('$nis_escaped', '$nama_anggota_escaped', '$kelas_escaped')";
            $insert = $koneksi->query($sql_insert);
            
            // Bersihkan semua output buffer sebelum redirect
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            if ($insert) {
                // Berhasil insert
                if (!headers_sent()) {
                    header("Location: index.php?page=presensi&status=success");
                    exit();
                } else {
                    echo '<script>window.location.href="index.php?page=presensi&status=success";</script>';
                    exit();
                }
            } else {
                // Jika insert gagal, redirect dengan error
                $db_error = $koneksi->error;
                if (!headers_sent()) {
                    header("Location: index.php?page=presensi&status=failed&error=" . urlencode($db_error));
                    exit();
                } else {
                    echo '<script>window.location.href="index.php?page=presensi&status=failed&error=' . urlencode($db_error) . '";</script>';
                    exit();
                }
            }
        } else {
            // Sudah presensi hari ini
            while (ob_get_level()) {
                ob_end_clean();
            }
            if (!headers_sent()) {
                header("Location: index.php?page=presensi&status=already");
                exit();
            } else {
                echo '<script>window.location.href="index.php?page=presensi&status=already";</script>';
                exit();
            }
        }
    }
    
    // Cek apakah sudah presensi hari ini
    $today = date('Y-m-d');
    $cek_hari_ini = $koneksi->query("SELECT * FROM tb_presensi WHERE (nis = '$nis' OR nama_anggota = '$nama_anggota') AND DATE(tgl_presensi) = '$today'");
    $sudah_absen = $cek_hari_ini->num_rows > 0;
    
    // Ambil history presensi berdasarkan nama atau NIS
    $history_query = $koneksi->query("SELECT * FROM tb_presensi WHERE nis = '$nis' OR nama_anggota = '$nama_anggota' ORDER BY tgl_presensi DESC LIMIT 10");
    
    // Tampilkan alert berdasarkan status
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        if ($status == 'success') {
            echo '<div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-bottom: 20px; border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-check-circle"></i> <strong>Berhasil!</strong> Presensi telah direkam.
                  </div>';
        } elseif ($status == 'failed') {
            echo '<div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-bottom: 20px; border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-exclamation-circle"></i> <strong>Gagal!</strong> Presensi gagal. Silakan coba lagi.
                  </div>';
        } elseif ($status == 'already') {
            echo '<div class="alert alert-warning alert-dismissible fade in" role="alert" style="margin-bottom: 20px; border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-info-circle"></i> Anda sudah melakukan presensi hari ini.
                  </div>';
        } elseif ($status == 'notfound') {
            echo '<div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-bottom: 20px; border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-exclamation-triangle"></i> Data anggota tidak ditemukan. Silakan hubungi admin.
                  </div>';
        }
    }
?>

<style>
.presensi-card {
    /* background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 40px; */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* border: 1px solid #f0f0f0; */
}

.presensi-card:hover {
    transform: translateY(-4px);
    /* box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); */
}

.presensi-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 16px;
    padding: 35px 60px;
    color: white;
    font-weight: 600;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    text-decoration: none;
    display: inline-block;
    min-width: 100%;
}

.presensi-button:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
    color: white;
    text-decoration: none;
}

.presensi-button:active {
    transform: translateY(-1px);
}

.presensi-button-disabled {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
    border: none;
    border-radius: 16px;
    padding: 35px 60px;
    color: white;
    font-weight: 600;
    font-size: 20px;
    cursor: not-allowed;
    box-shadow: 0 4px 15px rgba(86, 171, 47, 0.3);
    min-width: 220px;
}

.presensi-icon {
    font-size: 56px;
    margin-bottom: 20px;
    display: block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.history-card {
    /* background: #ffffff; */
    border-radius: 16px;
    /* box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); */
    padding: 0px 18px 18px 18px;
    border: 1px solid #f0f0f0;
}

.history-title {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.history-title i {
    color: #667eea;
}

.modern-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.modern-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modern-table thead th {
    padding: 18px 20px;
    font-weight: 600;
    text-align: left;
    border: none;
}

.modern-table tbody tr {
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.modern-table tbody tr:hover {
    background-color: #f8f9ff;
}

.modern-table tbody tr:last-child {
    border-bottom: none;
}

.modern-table tbody td {
    padding: 16px 20px;
    color: #4a5568;
}

.modern-table tbody tr.today-row {
    background: linear-gradient(90deg, #e8f5e9 0%, #ffffff 100%);
    /* border-left: 4px solid #56ab2f; */
}

.badge-modern {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-left: 10px;
    display: inline-block;
}

.info-text {
    color: #718096;
    font-size: 14px;
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.info-text i {
    color: #667eea;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #a0aec0;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.error-state {
    text-align: center;
    padding: 30px;
    color: #e53e3e;
    background: #fed7d7;
    border-radius: 12px;
    border: 1px solid #fc8181;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
}
</style>

<div class="row" style="margin: 0;">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fa fa-calendar-check-o" style="color: #667eea;"></i>
            Presensi
        </h1>
    </div>
</div>

<div class="row" style="margin: 0;">
    <!-- Button Presensi -->
    <div class="col-md-4" style="padding-right: 15px;">
        <div class="presensi-card">
            <div style="text-align: center;">
                <?php if ($nis): ?>
                    <?php if ($sudah_absen): ?>
                        <button class="presensi-button-disabled" disabled>
                            <i class="fa fa-check-circle presensi-icon"></i>
                            <span style="display: block;">Sudah Presensi</span>
                        </button>
                        <p class="info-text">
                            <i class="fa fa-info-circle"></i>
                            Anda sudah melakukan presensi hari ini
                        </p>
                    <?php else: ?>
                        <a href="?page=presensi&aksi=absen" class="presensi-button">
                            <i class="fa fa-hand-o-right presensi-icon"></i>
                            <span style="display: block;">Presensi</span>
                        </a>
                        <p class="info-text">
                            <i class="fa fa-info-circle"></i>
                            Klik untuk melakukan presensi
                        </p>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="?page=presensi&aksi=absen" class="presensi-button">
                        <i class="fa fa-hand-o-right presensi-icon"></i>
                        <span style="display: block;">Presensi</span>
                    </a>
                    <p class="info-text">
                        <i class="fa fa-info-circle"></i>
                        Klik untuk melakukan presensi sebagai <strong><?php echo htmlspecialchars($nama_user); ?></strong>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- History Presensi -->
    <div class="col-md-8" style="padding-left: 15px;">
        <div class="history-card">
            <h2 class="history-title">
                <i class="fa fa-history"></i>
                History Presensi
            </h2>
            <?php if ($history_query && $history_query->num_rows > 0): 
                $history_data = [];
                while ($row = $history_query->fetch_assoc()) {
                    $history_data[] = $row;
                }
            ?>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Presensi</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($history_data as $hist):
                                $tgl_presensi = date('d-m-Y H:i:s', strtotime($hist['tgl_presensi']));
                                $is_today = date('Y-m-d', strtotime($hist['tgl_presensi'])) == $today;
                            ?>
                            <tr class="<?php echo $is_today ? 'today-row' : ''; ?>">
                                <td style="font-weight: 600; color: #667eea;"><?php echo $no++; ?></td>
                                <td>
                                    <span style="font-weight: 500;"><?php echo $tgl_presensi; ?></span>
                                    <?php if ($is_today): ?>
                                        <span class="badge-modern">Hari Ini</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="background: #edf2f7; padding: 6px 14px; border-radius: 8px; font-weight: 600; color: #4a5568;">
                                        <?php echo $hist['kelas']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa fa-calendar-times-o"></i>
                    <h3 style="color: #a0aec0; margin-bottom: 10px;">Belum Ada History Presensi</h3>
                    <p style="color: #cbd5e0;">Mulai dengan melakukan presensi pertama Anda</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
} else {
    // Tampilan untuk Admin (tampilan lama)
?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Data Presensi
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div>
                            <a href="?page=presensi&aksi=tambah" class="btn btn-success" style="margin-top: 8px;"><i class="fa fa-plus"></i> Tambah Data</a>
                            </div><br>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Tanggal Presensi</th>
                                            <th width="19%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $no = 1;

                                        $sql = $koneksi->query("SELECT * FROM tb_presensi");

                                        while ($data = $sql->fetch_assoc()) {

                                    ?>

                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['nis'];?></td>
                                            <td><?php echo $data['nama_anggota'];?></td>
                                            <td><?php echo $data['kelas'];?></td>
                                            <td><?php echo $data['tgl_presensi'];?></td>
                                            <td>
                                                <a onclick="return confirm('Anda ingin menghapus?')" href="?page=presensi&aksi=hapus&id=<?php echo $data['id']; ?>" class="btn btn-danger" ><i class="fa fa-trash"></i> Hapus</a>

                                            </td>
                                        </tr>


                                        <?php  } ?>
                                    </tbody>

                                    </table>

                                  </div>
                        </div>
                     </div>
                   </div>
     </div>
<?php } ?>                           