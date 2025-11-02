<?php
// ============================================================
// IMPORT DATA Anggota DARI FILE EXCEL
// ============================================================

// Pastikan koneksi dan autoload sudah dipanggil
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../vendor/autoload.php';

// Namespace PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// ------------------------------------------------------------
// Cek tombol import ditekan
// ------------------------------------------------------------
if (isset($_POST['import'])) {

    // Cek apakah file diupload dengan benar
    if (!empty($_FILES['fileexcel']['name']) && $_FILES['fileexcel']['error'] === 0) {

        $file = $_FILES['fileexcel']['name'];
        $tmp  = $_FILES['fileexcel']['tmp_name'];
        $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // Validasi ekstensi file Excel
        if (in_array($ext, ['xls', 'xlsx'])) {

            // Buat folder uploads jika belum ada
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Pindahkan file upload ke folder uploads
            $target = $uploadDir . basename($file);
            if (!move_uploaded_file($tmp, $target)) {
                echo "<script>alert('Gagal mengunggah file!');history.back();</script>";
                exit;
            }

            try {
                // Baca file Excel
                $spreadsheet = IOFactory::load($target);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Lewati baris pertama (header)
                $first = true;
                $inserted = 0;

                foreach ($rows as $row) {
                    if ($first) {
                        $first = false;
                        continue;
                    }

                    // Gunakan null coalescing untuk mencegah undefined index
                    $nis        = $row[0] ?? '';
                    $nama    = $row[1] ?? '';
                    $tempat_lahir     = $row[2] ?? '';
                    $tgl_lahir = $row[3] ?? '';
                    $jk         = $row[4] ?? '';
                    $kelas  = $row[5] ?? '';

                    // Insert hanya jika judul tidak kosong
                    if (!empty(trim($nis))) {
                        $stmt = $koneksi->prepare("
                            INSERT INTO tb_anggota 
                            (nis, nama, tempat_lahir, tgl_lahir, jk, kelas)
                            VALUES (?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->bind_param(
                            "ssssss",
                            $nis, $nama, $tempat_lahir,
                            $tgl_lahir, $jk, $kelas
                        );
                        $stmt->execute();
                        $inserted++;
                    }
                }

                echo "<script>
                    alert('Import data anggota berhasil! Total data masuk: {$inserted}');
                    window.location='../../index.php?page=anggota';
                </script>";
            } catch (Exception $e) {
                echo "<script>
                    alert('Terjadi kesalahan saat membaca file Excel: " . addslashes($e->getMessage()) . "');
                    window.location='../../index.php?page=anggota&aksi=import';
                </script>";
            }
        } else {
            echo "<script>
                alert('Format file tidak didukung! Hanya .xls atau .xlsx.');
                window.location='../../index.php?page=anggota&aksi=import';
            </script>";
        }
    } else {
        echo "<script>
            alert('Silakan pilih file Excel terlebih dahulu!');
            window.location='../../index.php?page=anggota&aksi=import';
        </script>";
    }
}
?>
