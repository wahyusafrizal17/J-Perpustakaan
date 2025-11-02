<?php
// Cek apakah user adalah admin
if (!isset($_SESSION['admin'])) {
    ?>
    <script type="text/javascript">
        alert("Anda tidak memiliki akses untuk menghapus data!");
        window.location.href="?page=buku";
    </script>
    <?php
    exit();
}
	
	$id_buku = $_GET ['id_buku'];

	$koneksi->query("delete from tb_buku where id_buku ='$id_buku'");

?>


<script type="text/javascript">
		window.location.href="?page=buku";
</script>