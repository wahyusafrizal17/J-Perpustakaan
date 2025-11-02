<?php
// Konfigurasi database
$host     = "localhost";
$user     = "xiwaysta_xiway";
$password = "WahyuJR17_";
$database = "xiwaysta_perpustakaan";

// Buat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>