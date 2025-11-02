<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php'; // pastikan path vendor benar
use Dompdf\Dompdf;
use Dompdf\Options;

// -------------------------
// Koneksi Database
// -------------------------
$koneksi = new mysqli("localhost", "root", "", "db_perpustakaan");
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

// -------------------------
// Ambil Data Filter
// -------------------------
$tahun1 = $_POST['tahun1'] ?? '';
$tahun2 = $_POST['tahun2'] ?? '';
$result = null;
$judul = '';

if (!empty($tahun1) && !empty($tahun2)) {
    if (is_numeric($tahun1) && is_numeric($tahun2)) {
        $query = "SELECT * FROM tb_buku 
                  WHERE tahun_terbit BETWEEN '$tahun1' AND '$tahun2' 
                  ORDER BY tahun_terbit ASC";
        $result = $koneksi->query($query);
        $judul = "LAPORAN DATA BUKU<br><small>Periode Tahun $tahun1 s/d $tahun2</small>";
    } else {
        die("Input tahun tidak valid!");
    }
} else {
    $query = "SELECT * FROM tb_buku ORDER BY tahun_terbit ASC";
    $result = $koneksi->query($query);
    $judul = "LAPORAN DATA BUKU<br><small>Seluruh Data</small>";
}

// -------------------------
// HTML Tabel Laporan
// -------------------------
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        margin: 20px 30px;
    }
    h2 {
        text-align: center;
        margin-bottom: 10px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #000;
        padding: 6px 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        text-align: center;
    }
    .center { text-align: center; }
</style>
</head>
<body>
<h2>' . $judul . '</h2>
<table>
<thead>
<tr>
    <th>No</th>
    <th>Judul</th>
    <th>Pengarang</th>
    <th>Penerbit</th>
    <th>Tahun Terbit</th>
    <th>ISBN</th>
    <th>Jumlah Buku</th>
    <th>Lokasi</th>
    <th>Tanggal Input</th>
</tr>
</thead>
<tbody>';

$no = 1;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="center">' . $no++ . '</td>
            <td>' . htmlspecialchars($row['judul']) . '</td>
            <td>' . htmlspecialchars($row['pengarang']) . '</td>
            <td>' . htmlspecialchars($row['penerbit']) . '</td>
            <td class="center">' . htmlspecialchars($row['tahun_terbit']) . '</td>
            <td>' . htmlspecialchars($row['isbn']) . '</td>
            <td class="center">' . htmlspecialchars($row['jumlah_buku']) . '</td>
            <td>' . htmlspecialchars($row['lokasi']) . '</td>
            <td class="center">' . htmlspecialchars($row['tgl_input']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="9" class="center">Tidak ada data ditemukan</td></tr>';
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
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Tampilkan PDF di browser (tanpa download otomatis)
$dompdf->stream('Laporan_Buku.pdf', ['Attachment' => false]);
exit;
?>
