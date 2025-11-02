<?php
	error_reporting(0);
	ob_start();
  	
    session_start();


  $koneksi = new mysqli("localhost","xiwaysta_xiway","WahyuJR17_","xiwaysta_perpustakaan");

  if($_SESSION['admin'] || $_SESSION['user']){
        header("location:index.php");
    }else{

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PERPUSTAKAAN</title>
	<!-- BOOTSTRAP STYLES-->
    <!-- <link href="assets/css/bootstrap.css" rel="stylesheet" /> -->
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <!-- CSS -->
   <link rel="stylesheet" href="assets/css/login.css" />

</head>
<body>
  <div class="container">
    <div class="welcome-section">
      <img src="images/logo-smp.png" alt="">
      <h2>Sugeng Rawuh</h2>
      <p>Sugeng rawuh Sistem Perpustakaan SMP Negeri 1 Ambulu!
      Tempat terbaik untuk menumbuhkan minat baca dan memperluas wawasanmu.</p>
    </div>

    <div class="signin-section">
        <div class="form-box">
            <h2>Log In</h2>
            <form method="POST">
                <input type="text" name="nama" placeholder="Username" required />
                <input type="password" name="pass" placeholder="Password" required />
                <button type="submit" name="login">Masuk</button>
            </form>
        </div>
    </div>
  </div>

     <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>


<?php

if (isset($_POST['login'])) {

	$nama=$_POST['nama'];
	$pass=$_POST['pass'];

	$ambil = $koneksi->query("select * from tb_user where username='$nama' and password='$pass'");
	$data =$ambil->fetch_assoc();
	$ketemu = $ambil->num_rows;

	if($ketemu >=1){
                                    
    session_start();
    
    $_SESSION['username'] = $data ['username'];
    $_SESSION['pass'] = $data ['password'];
    $_SESSION['level'] = $data ['level'];
    
    
    if($data['level'] == "admin"){
        $_SESSION['admin'] = $data['id'];
        header("location:index.php");
        
    }else if($data['level']== "user"){
        $_SESSION['user'] = $data['id'];
        header("location:index.php");
        
    }
} else{
            ?>
                <script type="text/javascript">
                    alert("Username dan Password Anda Salah");
                </script>
            <?php
        }


}
}
?>
