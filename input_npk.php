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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $tanggal= $_POST['tgl_input_npk'];
    $pecah_tgl=explode("/",$tanggal);
    $thn=$pecah_tgl[2];
    $bln=$pecah_tgl[1];
    $tgl=$pecah_tgl[0];
    $tgl_hasil= $thn.'-'.$bln.'-'.$tgl;
  $insertSQL = sprintf("INSERT INTO nilai_perilaku_kerja (id_nilai_perilaku, id_pegawai, tgl_input_npk, orientasi_pelayanan, integritas, komitmen, disiplin, kerjasama, kepemimpinan, jumlah_npk, rata_npk) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_nilai_perilaku'], "text"),
                       GetSQLValueString($_POST['id_pegawai'], "text"),
                       GetSQLValueString($tgl_hasil, "date"),
                       GetSQLValueString($_POST['orientasi_pelayanan'], "text"),
                       GetSQLValueString($_POST['integritas'], "text"),
                       GetSQLValueString($_POST['komitmen'], "text"),
                       GetSQLValueString($_POST['disiplin'], "text"),
                       GetSQLValueString($_POST['kerjasama'], "text"),
                       GetSQLValueString($_POST['kepemimpinan'], "text"),
                       GetSQLValueString($_POST['jumlah_npk'], "text"),
                       GetSQLValueString($_POST['rata_npk'], "text"));

  // mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$insertSQL) or die(mysqli_error());

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

  mysqli_query($koneksi,"INSERT INTO history(nama,ket,tgl)VALUES('$nama_perubah','Menginput Data NPK Baru','$tgl_rubah')");

// ------------------ History ------------------

  $insertGoTo = "input_npk.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


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
                <em><a href="index.php">eKinerja</a> / <i style="color: black;"><!-- InstanceBeginEditable name="Location" --><em>Input NPK</em><!-- InstanceEndEditable --></i></em>
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
                        <h4 class="page-header"><!-- InstanceBeginEditable name="judul" -->Input NPK (Nilai Perilaku Kerja)<!-- InstanceEndEditable --></h4>
                    

                    </div>
                </div>
                <!-- /.row -->

                <div class="row">   
                    <!-- /.col-lg-8 --><!-- InstanceBeginEditable name="seting" -->
                    <div class="col-lg-12">
					<!-- InstanceEndEditable -->
                    
                    
                        <div class="panel panel-default">
                            <div style="font-size: 11px;" class="panel-heading"> 
                                <i class="fa fa-table fa-fw"></i><!-- InstanceBeginEditable name="Sub Judul" -->Nilai Perilaku Kerja Karyawan<!-- InstanceEndEditable --></div>
                            <!-- /.panel-heading -->
                            <div class="panel-body"><!-- InstanceBeginEditable name="Konten" -->
                              <?php 
                              $carikode = mysqli_query($koneksi,"SELECT max(id_nilai_perilaku) from nilai_perilaku_kerja") or die (mysqli_error());
                              $datakode = mysqli_fetch_array($carikode);
                              if($datakode) {
                                $nilaikode = substr($datakode[0], 3);
                                $kode = (int) $nilaikode;
                                $kode = $kode + 1;
                                $hasilkode = "NPK".str_pad($kode, 4, "0", STR_PAD_LEFT);
                              } else {
                                $hasilkode = "NPK0001";
                              }       
                            ?>
                            <form method="post" name="form1"  action="<?php echo $editFormAction; ?>">
                              <table width="100%" align="center">
                                <tr valign="baseline">
                                  <td width="14%" align="left" valign="middle" nowrap>ID NPK</td>
                                  <td width="1%" valign="middle">:</td>
                                  <td width="28%"><input name="id_nilai_perilaku" type="text" class="form-control" value="<?php echo $hasilkode ?>" readonly="readonly"></td>
                                  <td width="11%">&nbsp;</td>
                                  <td width="1%">&nbsp;</td>
                                  <td width="28%">&nbsp;</td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap> PEGAWAI</td>
                                  <td valign="middle">:</td>
                                  <td colspan="4">
                                  <select required class="form-control" style="cursor: pointer;" name="id_pegawai" id="id_pegawai">    
                                  <?php
                                            $tampil=mysqli_query($koneksi,"SELECT * FROM pegawai");
                                            $jml=mysqli_num_rows($tampil);
                                            if($jml > 0){
                                                echo"
                                                 <option value='' selected>-- Pilih --</option>";
                                                 while($pge=mysqli_fetch_array($tampil)){
                                                     echo "<option value=$pge[id_pegawai]>$pge[nama] | $pge[unit_kerja]";
                                                 } 
                                            } else {
                                                   echo "<option value=''>(kosong)</option>";
                                                 }
                                            ?>
                                        </select>
                                  </td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>&nbsp;</td>
                                  <td valign="middle">&nbsp;</td>
                                  <td height="30" valign="middle">Nilai Perilaku Kerja</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>ORIENTASI PELAYANAN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" required onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="orientasi_pelayanan" value="" id="op" size="32"></td>
                                  <td align="left" valign="middle" nowrap style="padding-left: 10px;">DISIPLIN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" required onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="disiplin" value="" id="dspln" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>INTEGRITAS</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" required onFocus="startCalc();" id="intg" onBlur="stopCalc();" class="form-control" name="integritas" value="" size="32"></td>
                                  <td align="left" valign="middle" nowrap style="padding-left: 10px;">KERJASAMA</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" required onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="kerjasama" value="" id="krjsm" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>KOMITMEN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" required onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="komitmen" value="" id="komit" size="32"></td>
                                  <td align="left" valign="middle" onFocus="startCalc();" onBlur="stopCalc();" nowrap style="padding-left: 10px;">KEPEMIMPINAN</td>
                                  <td valign="middle">:</td>
                                  <td><input type="number" required onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="kepemimpinan" value="" id="lead" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>JUMLAH NILAI</td>
                                  <td valign="middle">:</td>
                                  <td><input type="text" readonly style="font-size: 20px;" onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="jumlah_npk" id="jumlah_npk" value="" size="32">
                                   
                                  </td>
                                  <td align="left" valign="middle" nowrap style="padding-left: 10px;">TGL INPUT</td>
                                  <td valign="middle">:</td>
                                  <td><input type="text" readonly="" class="form-control" name="tgl_input_npk" id="tgl1" value="<?php echo date('d-m-Y') ?>" size="32"></td>
                                </tr>
                                <tr valign="baseline">
                                  <td align="left" valign="middle" nowrap>RATA-RATA</td>
                                  <td valign="middle">:</td>
                                  <td><input type="text" readonly style="font-size: 20px;" onFocus="startCalc();" onBlur="stopCalc();" class="form-control" name="rata_npk" id="rata_npk" value="" size="32"></td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>
                                    <button class="btn btn-primary"><i class="fa fa-plus-square"></i> Tambahkan</button>
                                    <button class="btn btn-danger" type="reset"><i class="fa fa-refresh"></i> Clear</button>
                                  </td>
                                </tr>
                              </table>
                              <input type="hidden" name="MM_insert" value="form1">
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
} else {
	?> 
	<script type="text/javascript">
		//alert("Silahkan Login Terlebih dahulu !!! ");
		window.location.href="login.php";
	</script>
	<?php
} 
 ?>
