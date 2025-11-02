<?php
// Cek apakah user adalah admin
if (!isset($_SESSION['admin'])) {
    ?>
    <script type="text/javascript">
        alert("Anda tidak memiliki akses untuk menghapus data!");
        window.location.href="?page=lokasi";
    </script>
    <?php
    exit();
}
	
	$id_lokasi = $_GET ['id_lokasi'];

	$koneksi->query("delete from tb_lokasi where id_lokasi ='$id_lokasi'");

?>


<script type="text/javascript">
		window.location.href="?page=lokasi";
</script>