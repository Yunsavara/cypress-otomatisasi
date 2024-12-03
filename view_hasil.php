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

// Ambil hasil ujian
$query_hasil = "SELECT h.skor, u.nama_ujian 
                FROM hasil_ujian h 
                JOIN ujian u ON h.ujian_id = u.ujian_id 
                WHERE h.peserta_id = '$peserta_id'";
$result_hasil = mysqli_query($conn, $query_hasil);
$hasil = mysqli_fetch_assoc($result_hasil);

// Ambil jawaban peserta
$query_jawaban = "SELECT j.soal_id, j.jawaban, s.jawaban_benar 
                  FROM jawaban_peserta j 
                  JOIN soal s ON j.soal_id = s.soal_id 
                  WHERE j.peserta_id = '$peserta_id'";
$result_jawaban = mysqli_query($conn, $query_jawaban);
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

    <p>Nama Ujian: <strong><?php echo $hasil['nama_ujian']; ?></strong></p>
    <p>Skor: <strong><?php echo $hasil['skor']; ?></strong></p>

    <h3>Jawaban Peserta</h3>
    <table border="1">
        <tr>
            <th>Soal ID</th>
            <th>Jawaban Peserta</th>
            <th>Jawaban Benar</th>
            <th>Status</th>
        </tr>
        <?php while ($jawaban = mysqli_fetch_assoc($result_jawaban)) { ?>
            <tr>
                <td><?php echo $jawaban['soal_id']; ?></td>
                <td><?php echo $jawaban['jawaban']; ?></td>
                <td><?php echo $jawaban['jawaban_benar']; ?></td>
                <td>
                    <?php
                    // Menentukan status lulus/tidak lulus
                    if ($jawaban['jawaban'] === $jawaban['jawaban_benar']) {
                        echo "Benar";
                    } else {
                        echo "Salah";
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard_peserta.php">Kembali ke Dashboard</a>
</body>
</html>
