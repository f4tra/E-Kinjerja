<?php
@session_start();
 include("Connections/koneksi.php");

if (@$_SESSION['Admin']) {
    header("location: index.php");
} else if(@$_SESSION['Pimpinan']) {
    header("location: index.php");
} else if(@$_SESSION['Pegawai']) {
    header("location: index.php");
}else {
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>e-Kinerja</title>
    <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <style type="text/css">
        body{
            /*background-image: url(img/bg.jpg);*/
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row" style="font-size: 11px;">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading" >
                        <h3 class="panel-title" ><img style="padding-left: 20px;" src="img/lg_diskominfo.png" height="50"></h3>
                    </div>
                    <div class="panel-body" style="font-size: 10px;">
                        <form role="form" method="post" action="">
                            <fieldset>
                                <div class="form-group input-group">
                                    <span style="font-size: 11px;" class="input-group-addon"> <i class="fa fa-user"></i></span>
                                    <input type="text" autofocus placeholder="Username" name="user" value="" class="form-control" >
                                </div>
                                 <div class="form-group input-group">
                                    <span style="font-size: 11px;" class="input-group-addon"> <i class="fa fa-key"></i></span>
                                    <input type="Password" name="pass" value="" class="form-control" >
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                    <input style="float: right" type="submit" name="login" class="btn btn-mini btn-primary" value="Masuk">
                                </div>
                             
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <?php

    // $user = @$_POST['user'];
    // $pass = @$_POST['pass'];
    $level = @$_POST['hak_akses'];
    $login = @$_POST['login'];
    $username = @$_POST['user'];
    $password = @$_POST['pass'];

    if($login) {
        // $koneksi = mysqli_connect("localhost","root","","db_ekinerja");
      $sql_login = mysqli_query($koneksi,"SELECT * from login where password = '$password' and username = '$username' ") or die (mysqli_error());
      $data = mysqli_fetch_array($sql_login);
      $cek = mysqli_num_rows($sql_login);
        if($cek >= 1) {
          
          if($data['hak_akses'] == "Admin") {
            @$_SESSION['Admin'] = $data['id_login'];
            ?> 
            <script type="text/javascript">
            alert("Selamat Datang Admin");
            window.location.href="index.php" ;
             </script>
            <?php
            #header("location: index.php");
          } else if($data['hak_akses'] == "Pimpinan") {
            @$_SESSION['Pimpinan'] = $data['id_login'];
            ?> 
            <script type="text/javascript">
            alert("Selamat Datang di E-Kinerja");
            window.location.href="index.php" ;
           </script>
            <?php
        } else if($data['hak_akses'] == "Pegawai") {
            @$_SESSION['Pegawai'] = $data['id_login'];
            ?> 
            <script type="text/javascript">
            alert("Selamat Datang di E-Kinerja Tenaga Honorer Kab. Batola");
            window.location.href="index.php" ;
           </script>
            <?php
        }
      } else {
       ?> <script>
              alert('Username / Password salah !!! ');
              window.location.href=window.location.href;
            </script><?php
      }
    }

?>
    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="js/sb-admin.js"></script>

</body>

</html>
<?php } ?>
