<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'u954659661_karir';

// Membuat koneksi ke database
$conn = mysqli_connect($host, $user, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
?>
