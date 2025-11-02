<?php
// Konfigurasi database
$host     = "localhost";  // default Laragon
$user     = "root";       // user default Laragon
$password = "WahyuJR17_";           // password kosong di Laragon
$database = "db_perpustakaan"; // ganti sesuai nama database kamu

// Buat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>