<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  // $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
	$tanggal= $_POST['tgl_input_npk'];
    $pecah_tgl=explode("/",$tanggal);
    $thn=$pecah_tgl[2];
    $bln=$pecah_tgl[1];
    $tgl=$pecah_tgl[0];
    $tgl_hasil= $thn.'-'.$bln.'-'.$tgl;
  $updateSQL = sprintf("UPDATE nilai_perilaku_kerja SET id_pegawai=%s, tgl_input_npk=%s, orientasi_pelayanan=%s, integritas=%s, komitmen=%s, disiplin=%s, kerjasama=%s, kepemimpinan=%s, jumlah_npk=%s, rata_npk=%s WHERE id_nilai_perilaku=%s",
                       GetSQLValueString($_POST['id_pegawai'], "text"),
                       GetSQLValueString($tgl_hasil, "date"),
                       GetSQLValueString($_POST['orientasi_pelayanan'], "text"),
                       GetSQLValueString($_POST['integritas'], "text"),
                       GetSQLValueString($_POST['komitmen'], "text"),
                       GetSQLValueString($_POST['disiplin'], "text"),
                       GetSQLValueString($_POST['kerjasama'], "text"),
                       GetSQLValueString($_POST['kepemimpinan'], "text"),
                       GetSQLValueString($_POST['jumlah_npk'], "text"),
                       GetSQLValueString($_POST['rata_npk'], "text"),
                       GetSQLValueString($_POST['id_nilai_perilaku'], "text"));

  // mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($koneksi,$updateSQL ) or die(mysql_error());

  // ------------------ History ------------------
    @session_start();
  if($_SESSION['Admin']) {
    $loginer = $_SESSION['Admin'];
  } else if($_SESSION['Pimpinan']){ 
    $loginer = $_SESSION['Pimpinan'];  
  } 
  $sql_login = mysql_query($koneksi,"SELECT * from login where id_login = '$loginer'") or die (mysql_error());
  $data_login = mysql_fetch_array($sql_login);
  $nama_perubah = $data_login['nama'];
  date_default_timezone_set('Asia/Jakarta');
  $tgl_rubah = date('Y-m-n H:i:s');

  mysql_query($koneksi,"INSERT INTO history(nama,ket,tgl)VALUES('$nama_perubah','Merubah Data Nilai Perilaku Kerja','$tgl_rubah')");

// ------------------ History ------------------

  $updateGoTo = "dt_npk.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edit_npk = "-1";
if (isset($_GET['id_nilai_perilaku'])) {
  $colname_edit_npk = $_GET['id_nilai_perilaku'];
}
// mysql_select_db($database_koneksi, $koneksi);
$query_edit_npk = sprintf("SELECT * FROM nilai_perilaku_kerja WHERE id_nilai_perilaku = %s", GetSQLValueString($colname_edit_npk, "text"));
$edit_npk = mysql_query($koneksi,$query_edit_npk ) or die(mysql_error());
$row_edit_npk = mysql_fetch_assoc($edit_npk);
$totalRows_edit_npk = mysql_num_rows($edit_npk);


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
  $sql_login = mysql_query($koneksi,"SELECT * from login where id_login = '$loginer'") or die (mysql_error());
  $data_login = mysql_fetch_array($sql_login);
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
                <em><a href="index.php">eKinerja</a> / <i style="color: black;"><!-- InstanceBeginEditable name="Location" -->Edit NPK<!-- InstanceEndEditable --></i></em>
                <li class="dropdown"> 
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages" style="font-size: 11px;">
                        <?php 
                        $sql_history = mysql_query($koneksi,"SELECT * FROM history order by id_history DESC LIMIT 0, 5");
                        while($data = mysql_fetch_array($sql_history)){
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
                        <h4 class="page-header"><!-- InstanceBeginEditable name="judul" -->Edit Nilai Prilaku Kerja (NPK)<!-- InstanceEndEditable --></h4>
                    

                    </div>
                </div>
                <!-- /.row -->

                <div class="row">   
                    <!-- /.col-lg-8 --><!-- InstanceBeginEditable name="seting" -->
                    <div class="col-lg-12">
					<!-- InstanceEndEditable -->
                    
                    
                        <div class="panel panel-default">
                            <div style="font-size: 11px;" class="panel-heading"> 
                                <i class="fa fa-table fa-fw"></i><!-- InstanceBeginEditable name="Sub Judul" -->Nilai Perilaku Kerja (NPK) <a href="dt_npk.php"><button class="btn btn-outline btn-danger"><i class="fa fa-reply"></i> Kembali</button></a><!-- InstanceEndEditable --></div>
                            <!-- /.panel-heading -->
                            <div class="panel-body"><!-- InstanceBeginEditable name="Konten" -->
                            <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                              <table width="100%" align="center">
                                <tr valign="baseline">
                                  <td width="15%" align="left" valign="middle" nowrap>ID-PEGAWAI</td>
                                  <td width="1%" valign="middle">:</td>
                                  <td><input readonly class="form-control" type="text" name="id_pegawai" value="<?php echo htmlentities($row_edit_npk['id_pegawai'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>ORIENTASI PELAYANAN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" onFocus="startCalc();" onBlur="stopCalc();" id="op" class="form-control" name="orientasi_pelayanan" value="<?php echo htmlentities($row_edit_npk['orientasi_pelayanan'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>INTEGRITAS</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" onFocus="startCalc();" onBlur="stopCalc();" id="intg" class="form-control" name="integritas" value="<?php echo htmlentities($row_edit_npk['integritas'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>KOMITMEN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" onFocus="startCalc();" onBlur="stopCalc();" id="komit" class="form-control" name="komitmen" value="<?php echo htmlentities($row_edit_npk['komitmen'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>DISIPLIN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" onFocus="startCalc();" onBlur="stopCalc();" id="dspln" class="form-control" name="disiplin" value="<?php echo htmlentities($row_edit_npk['disiplin'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>KERJASAMA</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" onFocus="startCalc();" onBlur="stopCalc();" id="krjsm" class="form-control" name="kerjasama" value="<?php echo htmlentities($row_edit_npk['kerjasama'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>KEPEMIMPINAN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" onFocus="startCalc();" onBlur="stopCalc();" id="lead" class="form-control" name="kepemimpinan" value="<?php echo htmlentities($row_edit_npk['kepemimpinan'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>JUMLAH</td>
                                  <td valign="middle">:</td>
                                  <td><input type="text" onFocus="startCalc();" onBlur="stopCalc();" id="jumlah_npk" readonly class="form-control" name="jumlah_npk" value="<?php echo htmlentities($row_edit_npk['jumlah_npk'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>RATA-RATA</td>
                                  <td valign="middle">:</td>
                                  <td><input type="text" onFocus="startCalc();" onBlur="stopCalc();" id="rata_npk" readonly class="form-control" name="rata_npk" value="<?php echo htmlentities($row_edit_npk['rata_npk'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>TGL INPUT</td>
                                  <td valign="middle">:</td>
                                  <?php
                                  $pecah_tgl=explode("-",$row_edit_npk['tgl_input_npk']);
                									$thn=$pecah_tgl[0];
                									$bln=$pecah_tgl[1];
                									$tgl=$pecah_tgl[2];
                									$tgl_npk= $tgl.'/'.$bln.'/'.$thn;
                								  ?>
                                  <td><input type="text" id="tgl1" class="form-control col-3" name="tgl_input_npk" value="<?php echo $tgl_npk; ?>" size="32"></td>
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
                              <input type="hidden" name="id_nilai_perilaku" value="<?php echo $row_edit_npk['id_nilai_perilaku']; ?>">
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
mysql_free_result($edit_npk);
 
} else {
	?> 
	<script type="text/javascript">
		//alert("Silahkan Login Terlebih dahulu !!! ");
		window.location.href="login.php";
	</script>
	<?php
} 
 ?>