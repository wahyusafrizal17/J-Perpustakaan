<script type="text/javascript">
    function validasi(form){
        if (form.nis.value=="") {
            alert("NIS Tidak Boleh Kosong");
            form.nis.focus();
            return(false);
        }if (form.nama.value=="") {
            alert("Nama Tidak Boleh Kosng");
            form.nama.focus();
            return(false);
        }if (form.tmpt_lahir.value=="") {
            alert("tempat Lahir Tidak Boleh Kosong");
            form.tmpt_lahir.focus();
            return(false);
        }if (form.kelas.value=="") {
            alert("Kelas Tidak Boleh Kosong");
            form.tmpt_lahir.focus();
            return(false);
        }if (form.tgl.value=="") {
            alert("Tanggal Tidak Boleh Kosong");
            form.tgl.focus();
            return(false);
        }
        return(true);
    }
</script>

<div class="panel panel-default">
<div class="panel-heading">
		Tambah Data Anggota
 </div> 
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            
            <form method="POST" onsubmit="return validasi(this)">
                <div class="form-group">
                    <label>NIS</label>
                    <input class="form-control" name="nis" id="nis" />
                    
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input class="form-control" name="nama" id="nama" />
                    
                </div>

                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input class="form-control" name="tmpt_lahir" id="tmpt_lahir" />
                    
                </div>

                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input class="form-control" type="date" name="tgl_lahir" id="tgl" />
                    
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label><br/>
                    <label class="form-check-inline">
                        <input type="radio" value="l" name="jk"  /> Laki-laki
                    </label>
                    <label class="form-check-inline">
                        <input type="radio" value="p" name="jk"  /> Perempuan
                    </label>
                    
                    
                </div>

                 <div class="form-group">
                    <label> Kelas</label>
                    <select class="form-control" name="kelas">
                        <option> == Pilih Kelas ==</option>
                        <option value="VII">VII</option>
                        <option value="VIII">VIII</option>
                        <option value="IX">IX</option>
                    </select>
                </div>

                 <div class="form-group">
                    <label> Abjad</label>
                    <select class="form-control" name="abjad">
                        <option> == Pilih Abjad ==</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                        <option value="H">H</option>
                        <option value="I">I</option>
                        <option value="J">J</option>
                        <option value="K">K</option>
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
 	$nama = $_POST ['nama'];
 	$tmpt_lahir = $_POST ['tmpt_lahir'];
 	$tgl_lahir = $_POST ['tgl_lahir'];
 	$jk = $_POST ['jk'];
 	$kelas = $_POST ['kelas'];
 	$abjad = $_POST ['abjad'];
    $kelas_lengkap = "$kelas " . "$abjad";
 	
 	$simpan = $_POST ['simpan'];


 	if ($simpan) {
 		
 		$sql = $koneksi->query("insert into tb_anggota (nis, nama, tempat_lahir, tgl_lahir, jk, kelas )values('$nis', '$nama', '$tmpt_lahir', '$tgl_lahir', '$jk', '$kelas_lengkap')");

 		if ($sql) {
 			?>
 				<script type="text/javascript">
 					
 					alert ("Data Berhasil Disimpan");
 					window.location.href="?page=anggota";

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
                             
                             

