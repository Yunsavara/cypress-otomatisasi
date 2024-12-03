<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'peserta') {
    header("Location: login.php");
    exit();
}

// Ambil peserta_id dari sesi
$peserta_id = $_SESSION['user_id'];

// Ambil ujian_id dari URL
$ujian_id = $_GET['ujian_id'];

// Ambil hasil ujian peserta dari database
$query_hasil = "SELECT * FROM hasil_ujian WHERE peserta_id = '$peserta_id' AND ujian_id = '$ujian_id'";
$result_hasil = mysqli_query($conn, $query_hasil);
$hasil = mysqli_fetch_assoc($result_hasil);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
</head>
<body>
    <h2>Hasil Ujian</h2>

    <?php if ($hasil): ?>
        <p>Skor Anda: <?php echo $hasil['skor']; ?> dari maksimal 100</p>
    <?php else: ?>
        <p>Hasil ujian tidak ditemukan.</p>
    <?php endif; ?>

    <a href="dashboard_peserta.php">Kembali ke Dashboard</a>
</body>
</html>
