<script type="text/javascript">
    function validasi(form){
        if (form.username.value == "") {
            alert("Username Tidak Boleh Kosong");
            form.username.focus();
            return false;
        }
        if (form.pass.value == "") {
            alert("Password Tidak Boleh Kosong");
            form.pass.focus();
            return false;
        }
        if (form.nama.value == "") {
            alert("Nama Tidak Boleh Kosong");
            form.nama.focus();
            return false;
        }
        // Foto tidak wajib, jadi tidak perlu dicek
        return true;
    }
</script>

<div class="panel panel-default">
<div class="panel-heading">Tambah Data Pengguna</div> 
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" enctype="multipart/form-data" onsubmit="return validasi(this)">
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" name="username" id="username" />
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="pass" type="password" id="pass" />
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input class="form-control" name="nama" id="nama" />
                </div>

                <div class="form-group">
                    <label>Level Akses</label>
                    <select class="form-control" name="level">
                        <option> == Pilih Akses Level == </option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>File Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto" />
                </div>
                
                <div>
                    <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>  
</div>  

<?php
if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $nama = $_POST['nama'];
    $level = $_POST['level'];

    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];

    // Cek apakah ada file yang diupload
    if (!empty($foto)) {
        $upload = move_uploaded_file($lokasi, "images/" . $foto);
    } else {
        $foto = ""; // atau bisa beri default: "default.jpg"
    }

    $sql = $koneksi->query("INSERT INTO tb_user (username, password, nama, level, foto)
                            VALUES ('$username', '$pass', '$nama', '$level', '$foto')");

    if ($sql) {
        echo "
        <script>
            alert('Data Berhasil Disimpan');
            window.location.href='?page=pengguna';
        </script>";
    }
}
?>
