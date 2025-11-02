<?php

$sql = $koneksi->query('SELECT nis FROM tb_anggota');
$data = $sql->fetch_all(MYSQLI_ASSOC);
// var_dump($data[4]['nis']);

?>

<div class="panel panel-default">
<div class="panel-heading">
		Tambah Data Presensi
 </div> 
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            
            <form method="POST" onsubmit="return validasi(this)">
                <div class="form-group">
                    <label>NIS</label>
                    <select class="form-control select2" name="nis" id="nis">
                        <option> == Pilih Kelas ==</option>
                        <?php foreach ($data as $nis ) : ?>
                                <option value="<?= $nis['nis'] ?>"><?= $nis['nis'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                	<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                </div>
         </div>

         </form>
      </div>
 </div>  
 </div>  
 </div>


 <?php

 	$nis = $_POST ['nis'];

    function fetch_anggota() {
        global $koneksi, $nis;
        $load_anggota = $koneksi->query("SELECT * FROM tb_anggota WHERE nis='$nis'");
        $data_anggota  = $load_anggota->fetch_all(MYSQLI_ASSOC)[0];
        
        $data = [];
        $nama = $data_anggota ['nama'];
        $kelas = $data_anggota ['kelas'];
        $data["nama"] = $nama;
        $data["kelas"] = $kelas;

        return $data;
    }
     
    
 	$simpan = $_POST ['simpan'];


 	if ($simpan) {
        $data = fetch_anggota();
        $nama_anggota = $data['nama'];
        $kelas = $data['kelas'];
 		$sql = $koneksi->query("insert into tb_presensi (nis, nama_anggota, kelas)values('$nis', '$nama_anggota', '$kelas')");

 		if ($sql) {
 			?>
 				<script type="text/javascript">
 					
 					alert ("Data Berhasil Disimpan");
 					window.location.href="?page=presensi";

 				</script>
 			<?php
 		} else{
            ?>
                <script type="text/javascript">
                    
                    alert ("Data Gagal Disimpan");

                </script>
            <?php
        }
 	}

 ?>
                             
                             

