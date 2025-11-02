<?php
    $koneksi = new mysqli("localhost","xiwaysta_xiway","WahyuJR17_","xiwaysta_perpustakaan");

    $filter_tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
    $filter_bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');

    $nama_bulan = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];

    $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $filter_bulan, $filter_tahun);
    $labels_tanggal = [];
    $data_presensi = array_fill(1, $jumlah_hari, 0);
    $data_peminjaman = array_fill(1, $jumlah_hari, 0);

    for ($i = 1; $i <= $jumlah_hari; $i++) {
        $labels_tanggal[] = $i;
    }

    // ====== PRESENSI ======
    $query_presensi = "
        SELECT DAY(tgl_presensi) AS tanggal, COUNT(*) AS jumlah
        FROM tb_presensi
        WHERE YEAR(tgl_presensi) = $filter_tahun
        AND MONTH(tgl_presensi) = $filter_bulan
        GROUP BY DAY(tgl_presensi)
        ORDER BY DAY(tgl_presensi)
    ";
    $result_presensi = $koneksi->query($query_presensi);

    while ($row = $result_presensi->fetch_assoc()) {
        $tgl = (int)$row['tanggal'];
        $data_presensi[$tgl] = (int)$row['jumlah'];
    }

    // ====== PEMINJAMAN ======
    $query_peminjaman = "
        SELECT DAY(tgl_input) AS tanggal, COUNT(*) AS jumlah
        FROM tb_transaksi
        WHERE YEAR(tgl_input) = $filter_tahun
        AND MONTH(tgl_input) = $filter_bulan
        GROUP BY DAY(tgl_input)
        ORDER BY DAY(tgl_input)
    ";
    $result_peminjaman = $koneksi->query($query_peminjaman);

    while ($row = $result_peminjaman->fetch_assoc()) {
        $tgl = (int)$row['tanggal'];
        $data_peminjaman[$tgl] = (int)$row['jumlah'];
    }
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/home.css">

<body>
    <center>
        <font size="+2" face="arial">Sistem Informasi Perpustakaan</font><br>
        <font size="+2" face="arial">SMP Negeri 1 Ambulu</font>
    </center>

    <br>

    <form method="GET" class="button-filter">
        <select name="tahun">
            <?php
            $tahun_sekarang = date('Y');
            for ($t = $tahun_sekarang; $t >= 2020; $t--) {
                $selected = ($t == $filter_tahun) ? 'selected' : '';
                echo "<option value='$t' $selected>$t</option>";
            }
            ?>
        </select>

        <select name="bulan">
            <?php
            foreach ($nama_bulan as $num => $nama) {
                $selected = ($num == $filter_bulan) ? 'selected' : '';
                echo "<option value='$num' $selected>$nama</option>";
            }
            ?>
        </select>

        <button type="submit">Tampilkan</button>
    </form>

    <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <div class="chart-container" style="flex: 1; min-width: 400px;">
            <p>Presensi per Tanggal - <?php echo $nama_bulan[$filter_bulan].' '.$filter_tahun; ?></p>
            <canvas id="chartPresensi"></canvas>
        </div>

        <div class="chart-container" style="flex: 1; min-width: 400px;">
            <p>Peminjaman per Tanggal - <?php echo $nama_bulan[$filter_bulan].' '.$filter_tahun; ?></p>
            <canvas id="chartPeminjaman"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labelsTanggal = <?php echo json_encode($labels_tanggal); ?>;
        const dataPresensi = <?php echo json_encode(array_values($data_presensi)); ?>;
        const dataPeminjaman = <?php echo json_encode(array_values($data_peminjaman)); ?>;

        // PRESENSI
        new Chart(document.getElementById('chartPresensi').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labelsTanggal,
                datasets: [{
                    label: 'Jumlah Presensi',
                    data: dataPresensi,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Tanggal' },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // PEMINJAMAN
        new Chart(document.getElementById('chartPeminjaman').getContext('2d'), {
            type: 'line',
            data: {
                labels: labelsTanggal,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: dataPeminjaman,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Tanggal' },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</body>
