<?php
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['peserta_id'])) {
    header("Location: login_peserta.php");
    exit();
}

include 'koneksi.php'; // Menggunakan koneksi database

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
    <title>Ujian Programmer</title>
</head>
<body>
    <h2>Ujian Programmer</h2>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($peserta['nama_lengkap']); ?></strong></p>

    <h3>Soal Ujian</h3>
    <form method="POST" action="proses_ujian.php">
        <label>1. Apa itu PHP?</label><br>
        <input type="radio" name="soal1" value="A"> A. Bahasa Pemrograman<br>
        <input type="radio" name="soal1" value="B"> B. Database<br>
        <input type="radio" name="soal1" value="C"> C. Framework<br>
        <br>

        <label>2. Apa kepanjangan dari HTML?</label><br>
        <input type="radio" name="soal2" value="A"> A. Hyper Text Markup Language<br>
        <input type="radio" name="soal2" value="B"> B. High Text Markup Language<br>
        <input type="radio" name="soal2" value="C"> C. Hyperlink Text Markup Language<br>
        <br>

        <button type="submit">Kirim Ujian</button>
    </form>
</body>
</html>
