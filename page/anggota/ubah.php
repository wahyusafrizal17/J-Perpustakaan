<?php
	$nis = $_GET['id'];

	$sql = $koneksi->query("select * from tb_anggota where nis = '$nis'");

    $data = $sql->fetch_all(MYSQLI_ASSOC)[0];
    $kelas_exploded = explode(' ', $data['kelas']);
    $kelas = $kelas_exploded[0];
    $abjad = $kelas_exploded[1];
    $jkl = $data['jk'];


?>

<div class="panel panel-default">
<div class="panel-heading">
		Ubah Data
 </div> 
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" >
                <div class="form-group">
                    <label>NIS</label>
                    <input class="form-control" name="nis" value="<?php echo $data['nis']?>" readonly/>
                    
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input class="form-control" name="nama" value="<?php echo $data['nama']?>"/>
                    
                </div>

                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input class="form-control" name="tmpt_lahir" value="<?php echo $data['tempat_lahir']?>" />
                    
                </div>

                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input class="form-control" type="date" name="tgl_lahir" value="<?php echo $data['tgl_lahir']?>"  />
                    
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label><br/>
                    <label class="form-check-inline">
                        <input type="radio" value="l" name="jk" <?php echo($jkl=="l")?"checked":"";?> /> Laki-laki
                    </label>
                    <label class="form-check-inline">
                        <input type="radio" value="p" name="jk" <?php echo($jkl=="p")?"checked":""; ?> /> Perempuan
                    </label>
                    
                    
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <select class="form-control" name="kelas" required>
                        <option value="">== Pilih Kelas ==</option>
                        <option value="VII"  <?= ($kelas == 'VII') ? 'selected' : ''; ?>>VII</option>
                        <option value="VIII" <?= ($kelas == 'VIII') ? 'selected' : ''; ?>>VIII</option>
                        <option value="IX"   <?= ($kelas == 'IX') ? 'selected' : ''; ?>>IX</option>
                    </select>
                </div>


                 <div class="form-group">
                    <label> Abjad</label>
                    <select class="form-control" name="abjad">
                        <option> == Pilih Abjad ==</option>
                        <option value="A" <?= ($abjad == 'A') ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?= ($abjad == 'B') ? 'selected' : ''; ?>>B</option>
                        <option value="C" <?= ($abjad == 'C') ? 'selected' : ''; ?>>C</option>
                        <option value="D" <?= ($abjad == 'D') ? 'selected' : ''; ?>>D</option>
                        <option value="E" <?= ($abjad == 'E') ? 'selected' : ''; ?>>E</option>
                        <option value="F" <?= ($abjad == 'F') ? 'selected' : ''; ?>>F</option>
                        <option value="G" <?= ($abjad == 'G') ? 'selected' : ''; ?>>G</option>
                        <option value="H" <?= ($abjad == 'H') ? 'selected' : ''; ?>>H</option>
                        <option value="I" <?= ($abjad == 'I') ? 'selected' : ''; ?>>I</option>
                        <option value="J" <?= ($abjad == 'J') ? 'selected' : ''; ?>>J</option>
                        <option value="K" <?= ($abjad == 'K') ? 'selected' : ''; ?>>K</option>
                    </select>
                </div>
                
                <div>
                	<input type="submit" name="simpan" value="Ubah" class="btn btn-primary">
                </div>
         </div>

         </form>
      </div>
</div>  
 </div>  
 </div>


 <?php
 	$nis = $_POST['nis'];
 	$nama = $_POST['nama'];
 	$tmpt_lahir = $_POST['tmpt_lahir'];
 	$tgl_lahir = $_POST['tgl_lahir'];
 	$jk = $_POST['jk'];
 	$kelas = $_POST['kelas'];
 	$abjad = $_POST['abjad'];
    $kelas_lengkap = "$kelas " . "$abjad";

    $simpan = $_POST['simpan'];

 	if ($simpan) {
 		
 		$sql = $koneksi->query("update tb_anggota set nama='$nama', tempat_lahir='$tmpt_lahir', tgl_lahir='$tgl_lahir', jk='$jk', kelas='$kelas_lengkap' where nis='$nis' ");
 		if ($sql) {
 			?>
 				<script type="text/javascript">
 					
 					alert ("Data Berhasil Disimpan");
 					window.location.href="?page=anggota";

 				</script>
 			<?php
 		}
 	}

 ?>
                             
                             

