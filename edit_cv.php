<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  // $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

    $nama_foto = time().'-'.$_FILES['pas_foto']['name'];
    $fisik_foto = $_FILES['pas_foto']['tmp_name'];

    $tanggal= $_POST['tgl_lahir'];
    $pecah_tgl=explode("/",$tanggal);
    $thn=$pecah_tgl[2];
    $bln=$pecah_tgl[1];
    $tgl=$pecah_tgl[0];
    $tgl_hasil= $thn.'-'.$bln.'-'.$tgl; 

    if(empty($_FILES['pas_foto']['name'])){
  $updateSQL = sprintf("UPDATE data_diri SET id_pegawai=%s, tempat_lahir=%s, tgl_lahir=%s, alamat_ktp=%s, no_ktp=%s, no_kk=%s, sd_thn=%s, smp_thn=%s, sma_thn=%s, univ_thn=%s, jurusan=%s, kampus=%s, hoby=%s, no_tlp=%s, Pengalaman_kerja=%s WHERE id_data_diri=%s",
                       GetSQLValueString($_POST['id_pegawai'], "text"),
                       GetSQLValueString($_POST['tempat_lahir'], "text"),
                       GetSQLValueString($tgl_hasil, "date"),
                       GetSQLValueString($_POST['alamat_ktp'], "text"),
                       GetSQLValueString($_POST['no_ktp'], "text"),
                       GetSQLValueString($_POST['no_kk'], "text"),
                       GetSQLValueString($_POST['sd_thn'], "text"),
                       GetSQLValueString($_POST['smp_thn'], "text"),
                       GetSQLValueString($_POST['sma_thn'], "text"),
                       GetSQLValueString($_POST['univ_thn'], "text"),
                       GetSQLValueString($_POST['jurusan'], "text"),
                       GetSQLValueString($_POST['kampus'], "text"),
                       GetSQLValueString($_POST['hoby'], "text"),
                       GetSQLValueString($_POST['no_tlp'], "text"),
                       GetSQLValueString($_POST['Pengalaman_kerja'], "text"),
                       GetSQLValueString($_POST['id_data_diri'], "text"));
} else {

    $updateSQL = sprintf("UPDATE data_diri SET id_pegawai=%s, tempat_lahir=%s, tgl_lahir=%s, alamat_ktp=%s, no_ktp=%s, no_kk=%s, sd_thn=%s, smp_thn=%s, sma_thn=%s, univ_thn=%s, jurusan=%s, kampus=%s, hoby=%s, no_tlp=%s, Pengalaman_kerja=%s, pas_foto=%s WHERE id_data_diri=%s",
                       GetSQLValueString($_POST['id_pegawai'], "text"),
                       GetSQLValueString($_POST['tempat_lahir'], "text"),
                       GetSQLValueString($tgl_hasil, "date"),
                       GetSQLValueString($_POST['alamat_ktp'], "text"),
                       GetSQLValueString($_POST['no_ktp'], "text"),
                       GetSQLValueString($_POST['no_kk'], "text"),
                       GetSQLValueString($_POST['sd_thn'], "text"),
                       GetSQLValueString($_POST['smp_thn'], "text"),
                       GetSQLValueString($_POST['sma_thn'], "text"),
                       GetSQLValueString($_POST['univ_thn'], "text"),
                       GetSQLValueString($_POST['jurusan'], "text"),
                       GetSQLValueString($_POST['kampus'], "text"),
                       GetSQLValueString($_POST['hoby'], "text"),
                       GetSQLValueString($_POST['no_tlp'], "text"),
                       GetSQLValueString($_POST['Pengalaman_kerja'], "text"),
                       GetSQLValueString($nama_foto, "text"),
                       GetSQLValueString($_POST['id_data_diri'], "text"));
                        copy($fisik_foto,"upload/pas_foto/".$nama_foto);
}

  // mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$updateSQL ) or die(mysqli_error());

  // ------------------ History ------------------
    @session_start();
  if($_SESSION['Admin']) {
    $loginer = $_SESSION['Admin'];
  } else if($_SESSION['Pimpinan']){ 
    $loginer = $_SESSION['Pimpinan'];  
  } 
  $sql_login = mysqli_query($koneksi,"SELECT * from login where id_login = '$loginer'") or die (mysqli_error());
  $data_login = mysqli_fetch_array($sql_login);
  $nama_perubah = $data_login['nama'];
  date_default_timezone_set('Asia/Jakarta');
  $tgl_rubah = date('Y-m-n H:i:s');

  mysqli_query($koneksi,"INSERT INTO history(nama,ket,tgl)VALUES('$nama_perubah','Merubah Data CV Pegawai','$tgl_rubah')");

// ------------------ History ------------------

  $updateGoTo = "dt_cv.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_dt_diri = "-1";
if (isset($_GET['id_data_diri'])) {
  $colname_dt_diri = $_GET['id_data_diri'];
}
// mysqli_select_db($database_koneksi, $koneksi);
$query_dt_diri = sprintf("SELECT * FROM data_diri WHERE id_data_diri = %s", GetSQLValueString($colname_dt_diri, "text"));
$dt_diri = mysqli_query($koneksi,$query_dt_diri ) or die(mysqli_error());
$row_dt_diri = mysqli_fetch_assoc($dt_diri);
$totalRows_dt_diri = mysqli_num_rows($dt_diri);

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/ekinerja.dwt.php" codeOutsideHTMLIsLocked="false" -->
<?php
@session_start();
if(@$_SESSION['Admin'] || @$_SESSION['Pimpinan'] || @$_SESSION['Pegawai']) {
?>  
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>e-Kinerja</title>
    <link rel="shortcut icon" href="img/logo_Batola.png" />  
    <!-- InstanceEndEditable -->
    <!-- Core CSS - Include with every page -->
    <link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet"> 
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery-ui.css">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <!--<link href="../css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="../css/plugins/timeline/timeline.css" rel="stylesheet">-->

    <!-- SB Admin CSS - Include with every page --> 
    <link href="css/sb-admin.css" rel="stylesheet"> 
    <!-- InstanceBeginEditable name="head" -->
    <!-- InstanceEndEditable -->
</head>
<?php
  if(@$_SESSION['Admin']) {
    $loginer = @$_SESSION['Admin'];
  } else if(@$_SESSION['Pimpinan']){ 
    $loginer = @$_SESSION['Pimpinan'];  
  } else if(@$_SESSION['Pegawai']){ 
    $loginer = @$_SESSION['Pegawai'];  
  } 
  $sql_login = mysqli_query($koneksi,"SELECT * from login where id_login = '$loginer'") or die (mysqli_error());
  $data_login = mysqli_fetch_array($sql_login);
?>
<body>

    <div id="wrapper">
    <?php
    if ($data_login['hak_akses'] == "Pimpinan" || $data_login['hak_akses'] == "Pegawai"){ ?>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="background-color: white;">
	<?php } else {
	?>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <?php } ?>  
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                </button>
                <a class="navbar-brand" style="color: #fc0; font-size: 30px; " href="index.php"><img src="img/lg_diskominfo.png" height="35" style="margin-top: -7px; padding-left: 20px;"></a>

            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <em><a href="index.php">eKinerja</a> / <i style="color: black;"><!-- InstanceBeginEditable name="Location" -->Edit CV Pegawai<!-- InstanceEndEditable --></i></em>
                <li class="dropdown"> 
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages" style="font-size: 11px;">
                        <?php 
                        $sql_history = mysqli_query($koneksi,"SELECT * FROM history order by id_history DESC LIMIT 0, 5");
                        while($data = mysqli_fetch_array($sql_history)){
                         ?>
                        <li>
                            <a href="#">
                                <div> 
                                    <strong><i class="fa fa-user"></i> <?php echo $data['nama'] ?></strong>
                                    <span class="pull-right text-muted">
                                        <em><?php echo $data['tgl'] ?></em>
                                    </span>
                                </div>
                                <div><em><?php echo $data['ket'] ?>...</em></div>
                            </a>
                        </li>
                        <li class="divider"></li>
                    <?php } ?>
                        <li>
                            <a class="text-center" href="history.php">
                                <strong>Read All Messages</strong>
                                <i class="fa fa-angle-right"></i> 
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu" style="font-size: 10px;">
                        <!--<li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li> -->
                        <li><a href="ganti_pw.php"><i class="fa fa-gear fa-fw"></i> Ganti Password</a>
                        </li>
                        <li class="divider"></li> 
                        <li><a onClick="return confirm('Keluar Aplikasi?')" href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a> 
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default navbar-static-side" style="background-color: #fff;" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                         <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                 <img src="img/agt.png" width="100" style="margin-left: 35px"><br>
                                <img src="img/red_dot.gif" width="20" style="margin-right: -20px; margin-left: 20px;">
                                <b style="color: black; padding-left: 20px;"><?php echo $data_login['nama'] ?></b> 
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-home fa-fw"></i> Dashboard</a>
                        </li>
                        
                        <?php if ($data_login['hak_akses'] == "Admin" || $data_login['hak_akses'] == "Pimpinan"){ ?>
                        <li>
                            <a href="#"><i class="fa fa-table fa-fw"></i> Manajemen Data<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            <?php if ($data_login['hak_akses'] == "Admin"){ ?>
                                <li>
                                    <a href="hak_akses.php"><i class="fa fa-edit fa-fw"></i> Hak Akses</a>
                                </li>
                            <?php } ?>
                            
                                <li>
                                    <a href="dt_pegawai.php"><i class="fa fa-edit fa-fw"></i> Pegawai</a>
                                </li>
                                 <li>
                                    <a href="dt_cv.php"><i class="fa fa-edit fa-fw"></i> Data CV Pegawai</a>
                                </li>
                                 <li>
                                    <a href="dt_jabatan.php"><i class="fa fa-edit fa-fw"></i> Jabatan</a>
                                </li>
                                
                            </ul>
                        </li>
                       <?php } ?>
                       <?php if ($data_login['hak_akses'] == "Admin"){ ?>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Manajemen Kerja<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#"><i class="fa fa-edit fa-fw"></i> Nilai Perilaku Kerja <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="input_npk.php">Input NPK</a>
                                        </li>
                                        <li>
                                            <a href="dt_npk.php">Semua Data</a>
                                        </li>
                            
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-edit fa-fw"></i> Tugas Kreatif <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                        <li>
                                            <a href="add_tgs_kreatif.php">Input Tugas Kreatif</a>
                                        </li>
                                        <li>
                                            <a href="dt_tgs_kreatif.php">Semua Data</a>
                                        </li>
                            
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-edit fa-fw"></i> Tugas Tambahan<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="add_tgs_tambahan.php">Input Tugas Tambahan</a>
                                        </li>
                                        <li>
                                            <a href="dt_tugas_tambahan.php">Semua Data</a>
                                        </li>
                            
                                    </ul>
                                </li>
                            </ul>
                        </li>
                         <?php } ?>
                        <?php if ($data_login['hak_akses'] == "Pegawai" || $data_login['hak_akses'] == "Admin" || $data_login['hak_akses'] == "Pimpinan"){ ?> 
                         <li>
                            <a href="hitung_nilai.php"><i class="fa fa-legal fa-fw"></i> Hitung Nilai Pegawai</a>
                        </li>
                         <?php } ?>
						<?php if ($data_login['hak_akses'] == "Admin" || $data_login['hak_akses'] == "Pimpinan"){ ?>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Laporan<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="laporan_bulanan.php"><i class="fa fa-print fa-fw"></i> Laporan Kinerja Bulanan</a>
                                </li>
                                <li>
                                    <a href="laporan_harian.php"><i class="fa fa-print fa-fw"></i> Laporan Kinerja Harian</a>
                                </li>
                               <!-- <li>
                                    <a href="../login.html"><i class="fa fa-check fa-fw"></i> Approval</a>
                                </li>-->
                            </ul>
                        </li>
                         <?php } ?>
                        <?php if ($data_login['hak_akses'] == "Admin" || $data_login['hak_akses'] == "Pimpinan"){ ?>
                        <li>
                            <a href="history.php"><i class="fa fa-refresh fa-fw"></i> History</a> 
                        </li>
                         <?php } ?>
                    </ul>
                </div>
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper"> 

                <div class="row">
                    <div class="col-lg-12"> 
                        <h4 class="page-header"><!-- InstanceBeginEditable name="judul" -->Edit CV Pegawai Honorer<!-- InstanceEndEditable --></h4>
                    

                    </div>
                </div>
                <!-- /.row -->

                <div class="row">   
                    <!-- /.col-lg-8 --><!-- InstanceBeginEditable name="seting" -->
                    <div class="col-lg-12">
					<!-- InstanceEndEditable -->
                    
                    
                        <div class="panel panel-default">
                            <div style="font-size: 11px;" class="panel-heading"> 
                                <i class="fa fa-table fa-fw"></i><!-- InstanceBeginEditable name="Sub Judul" -->Edit Data CV Pegawai Honorer DIskominfo Batola <a href="dt_cv.php"><button class="btn btn-outline btn-danger"><i class="fa fa-reply"></i> Kembali</button></a><!-- InstanceEndEditable --></div>
                            <!-- /.panel-heading -->
                            <div class="panel-body"><!-- InstanceBeginEditable name="Konten" -->
                            <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
                              <table width="100%" align="center">
                              <tr valign="baseline">
                                <td width="13%" align="left" valign="middle" nowrap></td>
                                <td valign="middle">&nbsp;</td>
                                <td><input type="file" name="pas_foto" id="pas_foto" value="<?php echo htmlentities($row_dt_diri['pas_foto'], ENT_COMPAT, 'utf-8'); ?>"></td>
                              </tr>
                              <tr valign="baseline">
                                <td align="left" valign="middle" nowrap>&nbsp;</td>
                                <td valign="middle">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>ID-PEGAWAI</td>
                                  <td width="1%" valign="middle">:</td>
                                  <td><input class="form-control" name="id_pegawai" type="text" value="<?php echo htmlentities($row_dt_diri['id_pegawai'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>TEMPAT LAHIR</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="tempat_lahir" value="<?php echo htmlentities($row_dt_diri['tempat_lahir'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>TGL LAHIR</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="tgl_lahir" id="tgl1" value="<?php echo htmlentities($row_dt_diri['tgl_lahir'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td nowrap align="left" valign="middle">ALAMAT</td>
                                  <td valign="middle">:</td>
                                  <td valign="middle"><textarea class="form-control" name="alamat_ktp" rows="2"><?php echo htmlentities($row_dt_diri['alamat_ktp'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>NO. KT P</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="no_ktp" value="<?php echo htmlentities($row_dt_diri['no_ktp'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>NO. K K</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="no_kk" value="<?php echo htmlentities($row_dt_diri['no_kk'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>SD</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control col-3" type="text" name="sd_thn" value="<?php echo htmlentities($row_dt_diri['sd_thn'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>SMP</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control col-3" type="text" name="smp_thn" value="<?php echo htmlentities($row_dt_diri['smp_thn'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>SMA</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control col-3" type="text" name="sma_thn" value="<?php echo htmlentities($row_dt_diri['sma_thn'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>KULIAH</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control col-3" type="text" name="univ_thn" value="<?php echo htmlentities($row_dt_diri['univ_thn'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>JURUSAN</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="jurusan" value="<?php echo htmlentities($row_dt_diri['jurusan'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>KAMPUS</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="kampus" value="<?php echo htmlentities($row_dt_diri['kampus'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>HOBBI</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="hoby" value="<?php echo htmlentities($row_dt_diri['hoby'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>NO. TLP</td>
                                  <td valign="middle">:</td>
                                  <td><input class="form-control" type="text" name="no_tlp" value="<?php echo htmlentities($row_dt_diri['no_tlp'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td nowrap align="left" valign="middle">PENGALAMAN KERJA</td>
                                  <td valign="middle">:</td>
                                  <td valign="middle"><textarea class="form-control" name="Pengalaman_kerja" rows="2"><?php echo htmlentities($row_dt_diri['Pengalaman_kerja'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
                                </tr>
                                <tr valign="baseline">
                                  <td nowrap align="right">&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>
                                    <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                </td>
                                </tr>
                              </table>
                              <input type="hidden" name="MM_update" value="form1">
                              <input type="hidden" name="id_data_diri" value="<?php echo $row_dt_diri['id_data_diri']; ?>">
                            </form>
                            <p>&nbsp;</p>
                            <!-- InstanceEndEditable --></div>
                        </div>
                    </div>
				<!-- InstanceBeginEditable name="code" -->
				<br>
				<!-- InstanceEndEditable -->
                </div>
                
                
            </div>
        </div>
    </div>
    <!-- /#wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script> 
    
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    
     <!-- Page-Level Plugin Scripts - Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <!--<script src="../js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="../js/plugins/morris/morris.js"></script>-->
    
	
    <!--<script language="javascript" src="../js/j-query.js"></script> -->
	
    <!-- SB Admin Scripts - Include with every page -->
    <script src="js/sb-admin.js"></script>

    <!-- Page-Level Demo Scripts - Dashboard - Use for reference -->
    <!--<script src="../js/demo/dashboard-demo.js"></script>-->
         
  <script src="js/jquery-ui.js"></script>

        <script type="text/javascript">
		  $(function() {
			$( "#tgl1,#tgl2" ).datepicker();
		  });
		
		function startCalc(){
  		interval = setInterval("calc()",1);}
  		function calc(){
      op = document.form1.op.value;
      intg = document.form1.intg.value;
      komit = document.form1.komit.value;
      dspln = document.form1.dspln.value;
      krjsm = document.form1.krjsm.value;
      lead = document.form1.lead.value;
     document.form1.rata_npk.value = (((op * 1) + (intg * 1) + (komit * 1)+ (dspln * 1)+ (krjsm * 1)+ (lead * 1)) / 6).toFixed(2);
  		document.form1.jumlah_npk.value = (op * 1) + (intg * 1) + (komit * 1)+ (dspln * 1)+ (krjsm * 1)+ (lead * 1) ;}
  		function stopCalc(){
  		clearInterval(interval);}
		  
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
    </script>
    

</body>

<!-- InstanceEnd --></html>

<?php
mysqli_free_result($dt_diri);
 
} else {
	?> 
	<script type="text/javascript">
		//alert("Silahkan Login Terlebih dahulu !!! ");
		window.location.href="login.php";
	</script>
	<?php
} 
 ?>
