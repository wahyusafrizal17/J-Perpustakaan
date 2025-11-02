<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php'; // Sesuaikan path vendor
use Dompdf\Dompdf;
use Dompdf\Options;

// -------------------------
// Koneksi Database
// -------------------------
require_once __DIR__ . '/../koneksi.php';

// -------------------------
// Ambil Data Filter Tanggal
// -------------------------
$tgl1 = $_POST['tanggal1'] ?? '';
$tgl2 = $_POST['tanggal2'] ?? '';

if (!empty($tgl1) && !empty($tgl2)) {
    $query = "SELECT * FROM tb_presensi 
              WHERE DATE(tgl_presensi) BETWEEN '$tgl1' AND '$tgl2' 
              ORDER BY tgl_presensi ASC";
    $judul = "LAPORAN DATA PRESENSI<br><small>Periode: $tgl1 s/d $tgl2</small>";
} else {
    $query = "SELECT * FROM tb_presensi ORDER BY tgl_presensi DESC";
    $judul = "LAPORAN DATA PRESENSI<br><small>Seluruh Data</small>";
}

$result = $koneksi->query($query);

// -------------------------
// Bangun HTML untuk PDF
// -------------------------
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; margin: 30px; }
    h2 { text-align: center; margin-bottom: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }
    .center { text-align: center; }
</style>
</head>
<body>
<h2>' . $judul . '</h2>
<table>
<thead>
<tr>
    <th>No</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Tanggal Presensi</th>
</tr>
</thead>
<tbody>';

// -------------------------
// Isi Data ke Tabel
// -------------------------
$no = 1;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '
        <tr>
            <td align="center">' . $no++ . '</td>
            <td>' . htmlspecialchars($row['nis']) . '</td>
            <td>' . htmlspecialchars($row['nama_anggota']) . '</td>
            <td>' . htmlspecialchars($row['kelas']) . '</td>
            <td>' . htmlspecialchars($row['tgl_presensi']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" class="center">Tidak ada data ditemukan</td></tr>';
}

$html .= '
</tbody>
</table>
<br><br>
<div style="text-align:right; margin-top:40px;">
    <p>Jember, ' . date('d F Y') . '</p>
    <p><b>Kepala Perpustakaan</b></p><br><br><br>
    <p><u>__________________________</u></p>
</div>
</body>
</html>';

// -------------------------
// Generate PDF dengan DOMPDF
// -------------------------
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// -------------------------
// Tampilkan PDF langsung di browser
// -------------------------
$dompdf->stream('Laporan_Presensi.pdf', ['Attachment' => false]);
exit;
?>
