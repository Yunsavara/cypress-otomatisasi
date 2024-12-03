<?php
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['peserta_id'])) {
    header("Location: login_peserta.php");
    exit();
}

include 'koneksi.php';

// Ambil data peserta
$peserta_id = $_SESSION['peserta_id'];
$query = "SELECT * FROM peserta WHERE peserta_id='$peserta_id'";
$result = mysqli_query($conn, $query);
$peserta = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Designer</title>
</head>
<body>
    <h2>Ujian Designer</h2>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($peserta['nama_lengkap']); ?></strong></p>

    <h3>Soal Ujian</h3>
    <form method="POST" action="proses_ujian.php">
        <label>1. Apa itu desain grafis?</label><br>
        <input type="radio" name="soal1" value="A"> A. Seni menggambar<br>
        <input type="radio" name="soal1" value="B"> B. Proses kreatif untuk menyampaikan pesan<br>
        <input type="radio" name="soal1" value="C"> C. Hanya menggunakan komputer<br>
        <br>

        <label>2. Apa yang dimaksud dengan warna komplementer?</label><br>
        <input type="radio" name="soal2" value="A"> A. Warna yang berseberangan di roda warna<br>
        <input type="radio" name="soal2" value="B"> B. Warna yang serupa<br>
        <input type="radio" name="soal2" value="C"> C. Warna yang sama<br>
        <br>

        <button type="submit">Kirim Ujian</button>
    </form>
</body>
</html>
