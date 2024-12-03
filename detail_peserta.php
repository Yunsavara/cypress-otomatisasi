<?php
session_start();
include 'koneksi.php'; // Menggunakan koneksi database

// Cek apakah HR sudah login
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'hr') {
    header("Location: login.php");
    exit();
}

// Ambil ID peserta dari parameter GET
if (isset($_GET['id'])) {
    $peserta_id = $_GET['id'];
} else {
    echo "ID peserta tidak ditemukan!";
    exit();
}

// Ambil data peserta
$query_peserta = "SELECT * FROM peserta WHERE peserta_id = '$peserta_id'";
$result_peserta = mysqli_query($conn, $query_peserta);
if (!$result_peserta) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
$peserta = mysqli_fetch_assoc($result_peserta);

// Pastikan peserta ditemukan
if (!$peserta) {
    echo "Peserta tidak ditemukan!";
    exit();
}

// Ambil total skor ujian peserta
$query_total_skor = "SELECT SUM(hu.skor) AS total_skor 
                     FROM hasil_ujian hu 
                     WHERE hu.peserta_id = '$peserta_id'";
$result_total_skor = mysqli_query($conn, $query_total_skor);
$total_skor_data = mysqli_fetch_assoc($result_total_skor);
$total_skor = $total_skor_data['total_skor'] ?? 0; // Jika tidak ada skor, default ke 0

// Ambil hasil ujian peserta (hanya satu ujian jika ada)
$query_hasil = "SELECT hu.skor, hu.ujian_id 
                FROM hasil_ujian hu 
                WHERE hu.peserta_id = '$peserta_id' 
                LIMIT 1"; // Ambil salah satu hasil ujian
$result_hasil = mysqli_query($conn, $query_hasil);
if (!$result_hasil) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
$hasil = mysqli_fetch_assoc($result_hasil);

// Ambil nama ujian berdasarkan ujian_id jika ada
$ujian_id = $hasil['ujian_id'] ?? null;
$nama_ujian = 'Ujian tidak ditemukan';
if ($ujian_id) {
    $query_nama_ujian = "SELECT nama_ujian FROM ujian WHERE ujian_id = '$ujian_id'";
    $result_nama_ujian = mysqli_query($conn, $query_nama_ujian);
    if ($result_nama_ujian) {
        $nama_ujian_data = mysqli_fetch_assoc($result_nama_ujian);
        $nama_ujian = $nama_ujian_data['nama_ujian'] ?? 'Ujian tidak ditemukan';
    }
}

// Ambil jawaban peserta beserta pilihan jawaban
$query_jawaban = "SELECT j.soal_id, j.jawaban, s.pertanyaan, s.jawaban_benar, s.pilihan_a, s.pilihan_b, s.pilihan_c, s.pilihan_d 
                  FROM jawaban_peserta j 
                  JOIN soal s ON j.soal_id = s.soal_id 
                  WHERE j.peserta_id = '$peserta_id'";
$result_jawaban = mysqli_query($conn, $query_jawaban);
if (!$result_jawaban) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Cek apakah ada jawaban yang ditemukan
if (mysqli_num_rows($result_jawaban) == 0) {
    $jawaban_message = "<p>Tidak ada jawaban yang ditemukan untuk peserta ini.</p>";
} else {
    $jawaban_message = ""; // Clear message if answers exist
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peserta</title>
    <!-- Menambahkan Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">Detail Peserta</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <p>Nama Lengkap: <strong><?php echo htmlspecialchars($peserta['nama_lengkap']); ?></strong></p>
                <p>Email: <strong><?php echo htmlspecialchars($peserta['email']); ?></strong></p>
                <p>Total Skor: <strong><?php echo $total_skor; ?></strong></p>
            </div>
        </div>

        <h3 class="text-center">Jawaban Peserta</h3>
        <?php echo $jawaban_message; ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Pertanyaan</th>
                    <th>Pilihan A</th>
                    <th>Pilihan B</th>
                    <th>Pilihan C</th>
                    <th>Pilihan D</th>
                    <th>Jawaban Peserta</th>
                    <th>Jawaban Benar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; // Inisialisasi nomor urut
                while ($jawaban = mysqli_fetch_assoc($result_jawaban)) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td> <!-- Menampilkan nomor urut -->
                        <td><?php echo htmlspecialchars($jawaban['pertanyaan']); ?></td>
                        <td><?php echo htmlspecialchars($jawaban['pilihan_a']); ?></td>
                        <td><?php echo htmlspecialchars($jawaban['pilihan_b']); ?></td>
                        <td><?php echo htmlspecialchars($jawaban['pilihan_c']); ?></td>
                        <td><?php echo htmlspecialchars($jawaban['pilihan_d']); ?></td>
                        <td><?php echo htmlspecialchars($jawaban['jawaban']); ?></td>
                        <td><?php echo htmlspecialchars($jawaban['jawaban_benar']); ?></td>
                        <td>
                            <?php
                            // Menentukan status benar/salah
                            echo ($jawaban['jawaban'] === $jawaban['jawaban_benar']) ? "<span class='text-success'>Benar</span>" : "<span class='text-danger'>Salah</span>";
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="dashboard_hr.php" class="btn btn-primary">Kembali ke Dashboard HR</a>
    </div>

    <!-- Menambahkan Bootstrap dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
