<?php
# FileName="Connection_php_mysqli.htm"
# Type="mysqli"
# HTTP="true"
$hostname_koneksi = "localhost";
$database_koneksi = "db_ekinerja";
$username_koneksi = "root";
$password_koneksi = "";
$koneksi = mysqli_connect($hostname_koneksi, $username_koneksi, $password_koneksi, $database_koneksi) or trigger_error(mysqli_error(),E_USER_ERROR); 
// mysqli_select_db($database_koneksi);
?>