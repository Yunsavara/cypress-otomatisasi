<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'peserta') {
    header("Location: login.php");
    exit();
}

// Ambil data peserta
$peserta_id = $_SESSION['user_id'];
$query_peserta = "SELECT * FROM peserta WHERE peserta_id = $peserta_id";
$result_peserta = mysqli_query($conn, $query_peserta);
$peserta = mysqli_fetch_assoc($result_peserta);

// Ambil ujian berdasarkan pekerjaan yang dilamar
$query_ujian = "
    SELECT u.*, 
           MAX(h.ujian_id) AS ujian_diambil -- Mengambil ujian terakhir yang diambil
    FROM ujian u
    LEFT JOIN hasil_ujian h ON u.ujian_id = h.ujian_id AND h.peserta_id = $peserta_id
    WHERE u.pekerjaan = '{$peserta['pekerjaan_yang_dilamar']}'
    GROUP BY u.ujian_id -- Mengelompokkan berdasarkan ujian
";
$ujian = mysqli_query($conn, $query_ujian);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta</title>
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Dashboard Peserta</h2>
                <h3>Selamat datang, <?php echo htmlspecialchars($peserta['nama_lengkap']); ?></h3>

                <!-- Tombol untuk melihat profil -->
                <div class="my-4">
                    <a href="lihat_profil.php" class="btn btn-info">Lihat Profil</a>
                </div>

                <h4 class="mt-4">Ujian Tersedia:</h4>
                <ul class="list-group">
                    <?php while ($ujian_data = mysqli_fetch_assoc($ujian)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($ujian_data['pekerjaan']); ?>
                            <?php if (is_null($ujian_data['ujian_diambil'])): ?>
                                <a href="ambil_ujian.php?ujian_id=<?php echo $ujian_data['ujian_id']; ?>" class="btn btn-primary btn-sm">Ambil Ujian</a>
                            <?php else: ?>
                                <span class="badge badge-success">Ujian Sudah Diambil</span>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
            </div>
        </div>
    </div>

    <!-- Tambahkan Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
