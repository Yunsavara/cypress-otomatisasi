<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'peserta') {
    header("Location: login.php");
    exit();
}

// Ambil pekerjaan dari URL
$pekerjaan = urldecode($_GET['pekerjaan']);

// Ambil ujian berdasarkan pekerjaan yang dilamar
$query_ujian = "SELECT * FROM ujian WHERE pekerjaan_id = (SELECT pekerjaan_id FROM pekerjaan WHERE nama_pekerjaan = '$pekerjaan')";
$result_ujian = mysqli_query($conn, $query_ujian);
$ujian = mysqli_fetch_assoc($result_ujian);
$ujian_id = $ujian['ujian_id'];

// Ambil soal untuk ujian tersebut
$query_soal = "SELECT * FROM soal WHERE ujian_id = $ujian_id";
$result_soal = mysqli_query($conn, $query_soal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian - <?php echo $ujian['judul_ujian']; ?></title>
</head>
<body>
    <h2>Ujian: <?php echo $ujian['judul_ujian']; ?></h2>
    <form action="proses_ujian.php" method="POST">
        <input type="hidden" name="ujian_id" value="<?php echo $ujian_id; ?>">
        <?php
        $no = 1;
        while ($soal = mysqli_fetch_assoc($result_soal)) {
            echo "<p>$no. " . $soal['soal_text'] . "</p>";
            echo "<input type='radio' name='jawaban[$no]' value='A'> " . $soal['pilihan_a'] . "<br>";
            echo "<input type='radio' name='jawaban[$no]' value='B'> " . $soal['pilihan_b'] . "<br>";
            echo "<input type='radio' name='jawaban[$no]' value='C'> " . $soal['pilihan_c'] . "<br>";
            echo "<input type='radio' name='jawaban[$no]' value='D'> " . $soal['pilihan_d'] . "<br>";
            $no++;
        }
        ?>
        <button type="submit">Kirim Jawaban</button>
    </form>
</body>
</html>
