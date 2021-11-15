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

if ((isset($_GET['id_login'])) && ($_GET['id_login'] != "")) {
  $deleteSQL = sprintf("DELETE FROM login WHERE id_login=%s",
                       GetSQLValueString($_GET['id_login'], "text"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "hak_akses.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_jabatan'])) && ($_GET['id_jabatan'] != "")) {
  $deleteSQL = sprintf("DELETE FROM jabatan WHERE id_jabatan=%s",
                       GetSQLValueString($_GET['id_jabatan'], "int"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "dt_jabatan.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_pegawai'])) && ($_GET['id_pegawai'] != "")) {
  $deleteSQL = sprintf("DELETE FROM pegawai WHERE id_pegawai=%s",
                       GetSQLValueString($_GET['id_pegawai'], "text"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "dt_pegawai.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_tgs_tambahan'])) && ($_GET['id_tgs_tambahan'] != "")) {
  // ------------------ Delte File -----------------
  $dt_file = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM tugas_tambahan WHERE id_tgs_tambahan='".$_GET['id_tgs_tambahan']."' "));
   unlink("upload/berkas/$dt_file[file_upload]");
  // ------------------ Delte File -----------------
  $deleteSQL = sprintf("DELETE FROM tugas_tambahan WHERE id_tgs_tambahan=%s",
                       GetSQLValueString($_GET['id_tgs_tambahan'], "text"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "dt_tugas_tambahan.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_tgs_kreatif'])) && ($_GET['id_tgs_kreatif'] != "")) {
  // ------------------ Delte File -----------------
  $dt_file = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM tugas_kreatif WHERE id_tgs_kreatif='".$_GET['id_tgs_kreatif']."' "));
   unlink("upload/berkas/$dt_file[file_upload]");
  // ------------------ Delte File ----------------
  $deleteSQL = sprintf("DELETE FROM tugas_kreatif WHERE id_tgs_kreatif=%s",
                       GetSQLValueString($_GET['id_tgs_kreatif'], "text"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "dt_tgs_kreatif.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_data_diri'])) && ($_GET['id_data_diri'] != "")) {
    // ------------------ Delte File -----------------
  $dt_pgw = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM data_diri WHERE id_data_diri='".$_GET['id_data_diri']."' "));
  mysqli_query($koneksi,"UPDATE pegawai SET detail='No' WHERE id_pegawai='".$dt_pgw['id_pegawai']."' ");
 unlink("upload/pas_foto/$dt_pgw[pas_foto]");
  // ------------------ Delte File -----------------
  $deleteSQL = sprintf("DELETE FROM data_diri WHERE id_data_diri=%s",
                       GetSQLValueString($_GET['id_data_diri'], "text"));



  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "dt_cv.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_history'])) && ($_GET['id_history'] != "")) {
  $deleteSQL = sprintf("DELETE FROM history WHERE id_history=%s",
                       GetSQLValueString($_GET['id_history'], "int"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "history.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['id_nilai_perilaku'])) && ($_GET['id_nilai_perilaku'] != "")) {
  $deleteSQL = sprintf("DELETE FROM nilai_perilaku_kerja WHERE id_nilai_perilaku=%s",
                       GetSQLValueString($_GET['id_nilai_perilaku'], "text"));

  //mysqli_select_db($database_koneksi, $koneksi);
  $Result1 = mysqli_query($koneksi,$deleteSQL) or die(mysqli_error());

  $deleteGoTo = "dt_npk.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


?>


