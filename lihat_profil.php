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

// Ambil data sertifikat dari tabel 'sertifikat'
$query_sertifikat = "SELECT * FROM sertifikat WHERE peserta_id = $peserta_id";
$result_sertifikat = mysqli_query($conn, $query_sertifikat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Peserta</title>
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4">Profil Peserta</h2>

                <table class="table table-bordered">
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?php echo htmlspecialchars($peserta['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($peserta['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo htmlspecialchars($peserta['alamat']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td><?php echo htmlspecialchars($peserta['tanggal_lahir']); ?></td>
                    </tr>
                    <tr>
                        <th>Lulusan</th>
                        <td><?php echo htmlspecialchars($peserta['lulusan']); ?></td>
                    </tr>
                    <tr>
                        <th>Pekerjaan yang Dilamar</th>
                        <td><?php echo htmlspecialchars($peserta['pekerjaan_yang_dilamar']); ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?php echo htmlspecialchars($peserta['username']); ?></td>
                    </tr>
                    <tr>
                        <th>CV</th>
                        <td>
                            <?php if (!empty($peserta['cv_file'])): ?>
                                <a href="uploads/<?php echo htmlspecialchars($peserta['cv_file']); ?>" target="_blank" class="btn btn-info btn-sm">Lihat CV</a>
                            <?php else: ?>
                                <span class="text-danger">CV belum diunggah</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <h4 class="mt-4">Sertifikat:</h4>
                <ul class="list-group">
                    <?php if (mysqli_num_rows($result_sertifikat) > 0): ?>
                        <?php while ($sertifikat = mysqli_fetch_assoc($result_sertifikat)): ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($sertifikat['nama_sertifikat']); ?> - 
                                <a href="uploads/<?php echo htmlspecialchars($sertifikat['file_sertifikat']); ?>" target="_blank" class="btn btn-primary btn-sm">Lihat Sertifikat</a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">Belum ada sertifikat yang diunggah.</li>
                    <?php endif; ?>
                </ul>

                <a href="dashboard_peserta.php" class="btn btn-secondary mt-4">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Tambahkan Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
