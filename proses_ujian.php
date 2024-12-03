<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'peserta') {
    header("Location: login.php");
    exit();
}

// Ambil ujian_id dari POST
if (isset($_POST['ujian_id'])) {
    $ujian_id = $_POST['ujian_id'];
} else {
    echo "Ujian ID tidak ditemukan!";
    exit();
}

// Ambil soal berdasarkan ujian_id
$query_soal = "SELECT * FROM soal WHERE ujian_id = $ujian_id";
$result_soal = mysqli_query($conn, $query_soal);
$score = 0;

// Cek apakah ada soal yang ditemukan
if (!$result_soal || mysqli_num_rows($result_soal) == 0) {
    echo "Tidak ada soal untuk ujian ini.";
    exit();
}

// Hitung skor berdasarkan jawaban peserta
while ($soal = mysqli_fetch_assoc($result_soal)) {
    $soal_id = $soal['soal_id'];
    $jawaban_benar = $soal['jawaban_benar']; // Kolom jawaban yang benar di tabel soal

    // Cek jawaban peserta
    if (isset($_POST['jawaban'][$soal_id]) && $_POST['jawaban'][$soal_id] == $jawaban_benar) {
        $score += 5; // Tambah 2 untuk setiap jawaban yang benar
    }
}

// Simpan hasil ke database
$peserta_id = $_SESSION['user_id'];
$query_insert = "INSERT INTO hasil_ujian (peserta_id, ujian_id, skor) VALUES ($peserta_id, $ujian_id, $score)";
mysqli_query($conn, $query_insert);

// Tampilkan hasil
echo "<h2>Hasil Ujian</h2>";
echo "<p>Skor Anda: $score</p>";
echo "<a href='dashboard_peserta.php'>Kembali ke Dashboard</a>";
?>
