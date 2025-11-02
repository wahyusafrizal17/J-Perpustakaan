<?php
	
	$id = $_GET ['id'];

	$koneksi->query("delete from tb_presensi where id ='$id'");

?>


<script type="text/javascript">
		window.location.href="?page=presensi";
</script>