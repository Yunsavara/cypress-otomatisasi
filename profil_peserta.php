<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'peserta') {
    header("Location: login.php");
    exit();
}

// Ambil data peserta dari database
$peserta_id = $_SESSION['user_id'];
$query = "SELECT * FROM peserta WHERE peserta_id = $peserta_id";
$result = mysqli_query($conn, $query);
$peserta = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Peserta</title>
</head>
<body>
    <h2>Profil Peserta</h2>
    <p><strong>Nama Lengkap:</strong> <?php echo $peserta['nama_lengkap']; ?></p>
    <p><strong>Email:</strong> <?php echo $peserta['email']; ?></p>
    <p><strong>Alamat:</strong> <?php echo $peserta['alamat']; ?></p>
    <p><strong>Tanggal Lahir:</strong> <?php echo $peserta['tanggal_lahir']; ?></p>
    <p><strong>Lulusan:</strong> <?php echo $peserta['lulusan']; ?></p>
    <p><strong>Pekerjaan yang Dilamar:</strong> <?php echo $peserta['pekerjaan_yang_dilamar']; ?></p>

    <a href="dashboard_peserta.php">Kembali ke Dashboard</a>
</body>
</html>
