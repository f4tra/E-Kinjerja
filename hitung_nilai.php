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

$colname_pgw = "-1";
if (isset($_GET['nik'])) {
  $colname_pgw = $_GET['nik'];
}
// mysqli_select_db($database_koneksi, $koneksi);
$query_pgw = sprintf("SELECT * FROM pegawai WHERE nik = %s", GetSQLValueString($colname_pgw, "text"));
$pgw = mysqli_query($koneksi,$query_pgw) or die(mysqli_error());
$row_pgw = mysqli_fetch_assoc($pgw);
$totalRows_pgw = mysqli_num_rows($pgw);

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
                <em><a href="index.php">eKinerja</a> / <i style="color: black;"><!-- InstanceBeginEditable name="Location" -->Beranda<!-- InstanceEndEditable --></i></em>
                <li class="dropdown"> 
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages" style="font-size: 11px;">
                        <?php 
                        $sql_history = mysqli_query("SELECT * FROM history order by id_history DESC LIMIT 0, 5");
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
                        <h4 class="page-header"><!-- InstanceBeginEditable name="judul" -->Pernilain Nilai Perilaku Kerja Pegawai<!-- InstanceEndEditable --></h4>
                    

                    </div>
                </div>
                <!-- /.row -->

                <div class="row">   
                    <!-- /.col-lg-8 --><!-- InstanceBeginEditable name="seting" -->
                    <div class="col-lg-12">
					<!-- InstanceEndEditable -->
                    
                    
                        <div class="panel panel-default">
                            <div style="font-size: 11px;" class="panel-heading"> 
                                <i class="fa fa-table fa-fw"></i><!-- InstanceBeginEditable name="Sub Judul" -->Perhitungan Perilaku Kerja Pegawai Honorer Diskominfo Batola<!-- InstanceEndEditable --></div>
                            <!-- /.panel-heading -->
                            <div class="panel-body"><!-- InstanceBeginEditable name="Konten" -->
                           <?php 
                                    if (@$_GET['nik'] > 0) { ?>
                                       <a href="hitung_nilai.php"> <button type="button" class="btn btn-success"><i class="fa fa-refresh"></i> Bersihkan</button></a>
                                    <?php } else {  ?>
                              <form name="form1" method="get" action="">
                                <table width="100%">
                                  <tr>
                                    <td width="17%">Masukkan NIK Pegawai</td>
                                    <td width="2%">:</td>
                                    <td>
                                    <?php if(@$_SESSION['Pegawai']) { ?>
                                        <input readonly="" class="form-control" type="text" value="<?php echo $data_login['nik'] ?>" name="nik">
                                    <?php } else { ?>
                                        <input hidden="" class="form-control" type="text" value="" name="nik">
                                    <?php } ?>
                                    </td>
                                    <td width="20%">
                                        <input class="form-control" type="number" name="tahun" value="<?php echo date('Y')?>">
                                    </td>
                                    <td> 
                                        <button class="btn btn-primary" name="cari" id="cari"><i class="fa fa-search"></i> Cari Data</button>
                                    </td>
                                  </tr>
                                </table>
                                   <?php } ?>
                                <hr>
                                <?php if ($totalRows_pgw > 0) { // Show if recordset not empty ?>
  <?php
  $tahun = $_GET['tahun'];
 $data_cv = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM data_diri WHERE id_pegawai ='".$row_pgw['id_pegawai']."' "));
  $data_npk = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM nilai_perilaku_kerja WHERE  year(tgl_input_npk)='$tahun' and id_pegawai ='".$row_pgw['id_pegawai']."' "));
  $jabatan = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM jabatan WHERE id_jabatan ='".$row_pgw['id_jabatan']."' "));
  
  $sql_TK = mysqli_query($koneksi,"SELECT * FROM tugas_kreatif WHERE year(tgl_input)='$tahun' AND id_pegawai ='".$row_pgw['id_pegawai']."'  ");
	 $row_TK = mysqli_num_rows($sql_TK);
  $sql_TT = mysqli_query($koneksi,"SELECT * FROM tugas_tambahan WHERE year(tgl)='$tahun' AND id_pegawai ='".$row_pgw['id_pegawai']."' ");
  $row_TT = mysqli_num_rows($sql_TT);
  
  $total_TT = mysqli_fetch_array(mysqli_query($koneksi,"SELECT SUM(nilai) AS total FROM tugas_tambahan WHERE year(tgl)='$tahun' AND id_pegawai ='".$row_pgw['id_pegawai']."'  "));
  $total_TK = mysqli_fetch_array(mysqli_query($koneksi,"SELECT SUM(nilai) AS total FROM tugas_kreatif WHERE year(tgl_input)='$tahun' AND id_pegawai ='".$row_pgw['id_pegawai']."'  "));
  
  ?>
  <div>
<script>
  function printContent(el){
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
  }
  </script>
<div align="left"><button onclick="printContent('dataPrint')"><i class="fa fa-print"></i> Cetak Hasil Penilaian</button></div>
<fieldset name="dataPrint" id="dataPrint">
<div name="judul">
    <img src="img/logo_Batola.png" style="float: left; margin-right: 10px;" width="60">
    <h4 style="line-height: 6px;"><b>DISKOMINFO KABUPATEN BARITO KUALA</b></h4>
  <h5 style="line-height: 2px;">PERHITUNGAN NILAI PERILAKU KINERJA PEGAWAI</h5>
  <span style="line-height: -3px; font-size: 10px;">Jl. KTM, Ulu Benteng, Marabahan, Kabupaten Barito Kuala, Kalimantan Selatan 70513</span>
  <p style="font-size: 10px;">Dihitung Tanggal <?php echo date('d-m-Y')?> <img src="img/lg_diskominfo.png" height="35" style="float: right; padding-right: 36px;"></p>
   
  </div>
  <hr>
  <table width="80%" class="table table-striped table-bordered" align="center">
    <tr>
      <td width="14%">N A M A</td>
      <td>: <strong><?php echo $row_pgw['nama']; ?></strong></td>
      <td>No. KTP</td>
      <td>: <?php echo $data_cv['no_ktp']; ?></td>
      <td>&nbsp;</td>
      <td align="center" colspan="2">Perhitungan Nilai Tahun : <?php echo @$_GET['tahun']?></td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
        <tr>
          <td>N I K</td>
          <td>: <?php echo $row_pgw['nik']; ?></td>
          <td>No. KK</td>
          <td>: <?php echo $data_cv['no_kk']; ?></td>
          <td>&nbsp;</td>
          <td colspan="2" rowspan="7" align="center"><img src="upload/pas_foto/<?php echo $data_cv['pas_foto']; ?>" width="150"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>BIDANG</td>
          <td><?php echo $row_pgw['bidang']; ?></td>
          <td>JABATAN</td>
          <td>: <?php echo $jabatan['nm_jabatan']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>JENIS KELAMIN</td>
          <td>: <?php echo $row_pgw['jk']; ?></td>
          <td>UNIT KERJA</td>
          <td>: <?php echo $row_pgw['unit_kerja']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>STATUS</td>
          <td> : <?php echo $row_pgw['status']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><strong>I. NILAI PERILAKU KERJA</strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>ORIENTASI PELAYANAN</td>
          <td align="center"><?php echo $data_npk['orientasi_pelayanan']; ?></td>
          <td>DISIPLIN</td>
          <td align="center"><?php echo $data_npk['disiplin']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>INTEGRITAS</td>
          <td align="center"><?php echo $data_npk['integritas']; ?></td>
          <td>KERJASAMA</td>
          <td align="center"><?php echo $data_npk['kerjasama']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>KOMITMEN</td>
          <td align="center"><?php echo $data_npk['komitmen']; ?></td>
          <td>KEPEMIMPINAN</td>
          <td align="center"><?php echo $data_npk['kepemimpinan']; ?></td>
          <td>&nbsp;</td>
          <td align="center" colspan="2">Predikat Kinerja: 
            <?php 
            $total_nilai= $total_TK['total']+$total_TT['total']+$data_npk['jumlah_npk'];
          $total_tugas = $row_TK + $row_TT;
          $total_keseluruhan =( $total_nilai / ( $total_tugas+6 ) +$total_tugas);
            if($total_keseluruhan== 0){
             echo " <b>TIDAK ADA NILAI</b>";
         } else if($total_keseluruhan== 10 || $total_keseluruhan<=54){
             echo " <b>D</b>";
         } else if($total_keseluruhan== 55 || $total_keseluruhan<=59){
             echo " <b>D+</b>";

         } else if($total_keseluruhan== 60 || $total_keseluruhan<=64){
             echo " <b>C-</b>";
         } else if($total_keseluruhan== 65 || $total_keseluruhan<=69){
             echo " <b>C</b>";
         } else if($total_keseluruhan== 70 || $total_keseluruhan<=74){
             echo " <b>C+</b>";

         }else if($total_keseluruhan== 75 || $total_keseluruhan<=80){
             echo " <b>B-</b>";
         }else if($total_keseluruhan== 81 || $total_keseluruhan<=85){
             echo " <b>B</b>";
         }else if($total_keseluruhan== 86 || $total_keseluruhan<=90){
             echo " <b>B+</b>";

         } else if($total_keseluruhan== 91 || $total_keseluruhan<=95){
             echo " <b>A-</b>";
         } else if($total_keseluruhan== 95 || $total_keseluruhan<=100){
             echo " <b>A</b>";
         }
             ?>
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>JUMLAH NILAI NPK</td>
          <td align="center"><?php echo $data_npk['jumlah_npk']; ?></td>
          <td>RATA-RATA</td>
          <td align="center"><?php echo $data_npk['rata_npk']; ?></td>
          <td>&nbsp;</td>
          <td colspan="2" rowspan="3" align="center">
         <?php 
         $total_nilai= $total_TK['total']+$total_TT['total']+$data_npk['jumlah_npk'];
          $total_tugas = $row_TK + $row_TT;
          $total_keseluruhan =( $total_nilai / ( $total_tugas+6 ) +$total_tugas);

		 if($total_keseluruhan== 0){
             echo " <h3>TIDAK ADA NILAI</h3>";
         } else if($total_keseluruhan== 54 || $total_keseluruhan<=59){
			 echo " <h3>KURANG BAIK</h3>";
		 } else if($total_keseluruhan== 66 || $total_keseluruhan<=79){
			 echo " <h3>CUKUP</h3>";
		 }else if($total_keseluruhan== 80 || $total_keseluruhan<=89){
			 echo " <h3>BAIK</h3>";
		 } else if($total_keseluruhan== 90 || $total_keseluruhan<=100){
			 echo " <h3>SANGAT BAIK</h3>";
		 }

         // -------------------------- Krriteria
         
		 ?>
        
         <span>Total Nilai : <?php echo $total_nilai ?><br>
         	Rata-rata Total : <?php echo number_format($total_keseluruhan,2,'.','') ?>
         </span>
          
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center">Total Nilai Tugas Kreatif</td>
          <td align="center">Total Nilai Tugas Tambahan</td>
          <td colspan="2">(TOTAL NILAI / (Total Tugas Dikerjakan + total NPK)) + Total Tugas</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center"><?php echo $total_TK['total']; ?></td>
          <td align="center"><?php echo $total_TT['total']; ?></td>
          <td colspan="2">(<?php echo $total_nilai; ?> / (<?php echo $total_tugas; ?> + 6)) + <?php echo $total_tugas; ?> = <?php echo number_format($total_keseluruhan,2,'.',''); ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="8"><strong>II. DATA TUGAS KREATIF</strong></td>
        </tr>
        <tr>
          <td colspan="8">
          <table width="100%" border="1">
          <tr>
          	<td align="center">No</td>
            <td align="center">Keterangan Tugas</td>
            <td align="center">File</td>
            <td align="center">Tanggal</td>
            <td align="center">Nilai</td>
          </tr>
		  <?php 
		  $no=0;
		  
		  while($tk=mysqli_fetch_array($sql_TK)){ $no++?>

              <tr>
              <td align="center"><?php echo $no;?></td>
              <td align="center"><?php echo $tk['keterangan'];?></td>
              <td align="center"><?php echo $tk['file_upload'];?></td>
              <td align="center"><?php echo $tk['tgl_input'];?></td>
               <td align="center"><?php echo $tk['nilai'];?></td>
              </tr>
			<?php } ?>
         </table>
			  
          </td>
        </tr>
        <tr>
          <td colspan="8"><strong>III. DATA TUGAS TAMBAHAN</strong></td>
        </tr>
        <tr>
          <td colspan="8">
          	<table width="100%" border="1">
          <tr>
          	<td align="center">No</td>
            <td align="center">Keterangan Tugas</td>
            <td align="center">File</td>
            <td align="center">Tanggal</td>
            <td align="center">Nilai</td>
          </tr>
		  <?php 
		  $no=0;
		  
		  $cek = mysqli_num_rows($sql_TT);
		  while($tt=mysqli_fetch_array($sql_TT)){ 
		  $no++;
		  if($cek > 0){
		  ?>
          <tr>
                  <td align="center"><?php echo $no.'.';?></td>
                  <td align="center"><?php echo $tt['ket'];?></td>
                  <td align="center"><?php echo $tt['file_upload'];?></td>
                  <td align="center"><?php echo $tt['tgl'];?></td>
                  <td align="center"><?php echo $tt['nilai'];?></td>
              </tr>
			<?php } else {?>
				<tr>
                 <td colspan="5">asdasd</td>
                 
              </tr>
			<?php } } ?>
         </table>
          </td>
          </tr>
        <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php } while ($row_pgw = mysqli_fetch_assoc($pgw)); ?>
  </table>
</fieldset>
  <?php } // Show if recordset not empty ?>

                            </form>
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
mysqli_free_result($pgw);
 
} else {
	?> 
	<script type="text/javascript">
		//alert("Silahkan Login Terlebih dahulu !!! ");
		window.location.href="login.php";
	</script>
	<?php
} 
 ?>
