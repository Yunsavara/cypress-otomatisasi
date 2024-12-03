<?php
session_start();
include 'koneksi.php'; // Database connection

// Check if HR is logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'hr') {
    header("Location: login.php");
    exit();
}

// Retrieve participants' data and exam total scores
$query = "SELECT p.*, SUM(hu.skor) as total_skor 
          FROM peserta p 
          LEFT JOIN hasil_ujian hu ON p.peserta_id = hu.peserta_id 
          GROUP BY p.peserta_id";
$result = mysqli_query($conn, $query);

// Retrieve the username of the logged-in HR
$hr_id = $_SESSION['user_id'];
$query_hr = "SELECT * FROM hr WHERE hr_id='$hr_id'";
$result_hr = mysqli_query($conn, $query_hr);
$hr = mysqli_fetch_assoc($result_hr);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HR</title>
    <!-- Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_hr.php'; ?>
    <div class="container">
        <h2 class="my-4">Dashboard HR</h2>
        
        <!-- Display the name of the logged-in HR -->
        <p>Selamat datang, <strong><?php echo htmlspecialchars($hr['username']); ?></strong>!</p>

        <!-- Display success or failure message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Clear message after displaying
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Add Career Button -->
        <a href="tambah_karir.php" class="btn btn-primary mb-4" id="tambah_lowongan_karir">Tambah Lowongan Karir</a>
        
        
        <h3 class="text-center">Daftar Peserta</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark text-center align-middle">
                    <tr>
                        <th class="align-middle">Nama Lengkap</th>
                        <th class="align-middle">Email</th>
                        <th class="align-middle">Alamat</th>
                        <th class="align-middle">Tanggal Lahir</th>
                        <th class="align-middle">Lulusan</th>
                        <th class="align-middle">Pekerjaan yang Dilamar</th>
                        <th class="align-middle">Total Skor</th>
                        <th class="align-middle">Keterangan</th>
                        <th class="align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($peserta = mysqli_fetch_assoc($result)) { 
                        // Fuzzy logic for determining pass status
                        if (is_null($peserta['total_skor']) || $peserta['total_skor'] == 0) {
                            $keterangan = 'Belum Ujian';
                        } elseif ($peserta['total_skor'] < 60) {
                            $keterangan = 'Tidak Lulus';
                        } elseif ($peserta['total_skor'] >= 61 && $peserta['total_skor'] <= 70) {
                            $keterangan = 'Lulus Bersyarat';
                        } else {
                            $keterangan = 'Lulus';
                        }
                    ?>
                        <tr class="text-center align-middle">
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['nama_lengkap']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['email']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['alamat']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['tanggal_lahir']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['lulusan']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['pekerjaan_yang_dilamar']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($peserta['total_skor']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($keterangan); ?></td>
                           <td class="align-middle">
                                <div class="d-flex flex-column">
                                    <a href="detail_peserta.php?id=<?php echo $peserta['peserta_id']; ?>" class="btn btn-info btn-sm w-100 mb-2">View Jawaban</a>
                                    <a href="edit.php?id=<?php echo $peserta['peserta_id']; ?>" class="btn btn-warning btn-sm w-100 mb-2">Edit</a>
                                    <button class="btn btn-info btn-sm w-100 mb-2" data-toggle="modal" data-target="#cvModal<?php echo $peserta['peserta_id']; ?>">Lihat CV</button>
                                    <button class="btn btn-info btn-sm w-100 mb-2" data-toggle="modal" data-target="#sertifikatModal<?php echo $peserta['peserta_id']; ?>">Lihat Sertifikat</button>
                                    <button class="btn btn-info btn-sm w-100 mb-2" data-toggle="modal" data-target="#pernyataanModal<?php echo $peserta['peserta_id']; ?>">Lihat Pernyataan</button>
                                    <a href="reset_ujian.php?peserta_id=<?php echo $peserta['peserta_id']; ?>" class="btn btn-danger btn-sm w-100" onclick="return confirm('Apakah Anda yakin ingin mereset ujian peserta ini?')">Reset Ujian</a>
                                </div>
                           </td>
                        </tr>

                        <!-- CV Modal -->
                        <div class="modal fade" id="cvModal<?php echo $peserta['peserta_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="cvModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cvModalLabel">CV - <?php echo htmlspecialchars($peserta['nama_lengkap']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe src="uploads/<?php echo htmlspecialchars($peserta['cv_file']); ?>" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pernyataan Modal -->
                        <div class="modal fade" id="pernyataanModal<?php echo $peserta['peserta_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="pernyataanModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pernyataanModalLabel">Pernyataan - <?php echo htmlspecialchars($peserta['nama_lengkap']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Tujuan melamar:</strong> <?php echo htmlspecialchars($peserta['tujuan_melamar']); ?></p>
                                        <p><strong>Bidang pekerjaan yang disenangi:</strong> <?php echo htmlspecialchars($peserta['bidang_disenangi']); ?></p>
                                        <p><strong>Bidang pekerjaan yang tidak disenangi:</strong> <?php echo htmlspecialchars($peserta['bidang_tidak_disenangi']); ?></p>
                                        <p><strong>Pengetahuan dan keahlian yang dikuasai:</strong> <?php echo htmlspecialchars($peserta['pengetahuan_keahlian']); ?></p>
                                        <p><strong>Gaji dan tunjangan yang diharapkan:</strong> <?php echo htmlspecialchars($peserta['gaji_harapan']); ?></p>
                                         <p><strong>Kapan anda dapat mulai bekerja ?:</strong> <?php echo htmlspecialchars($peserta['kapan_bisa_bekerja']); ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Certificate Modal -->
                        <div class="modal fade" id="sertifikatModal<?php echo $peserta['peserta_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="sertifikatModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="sertifikatModalLabel">Sertifikat - <?php echo htmlspecialchars($peserta['nama_lengkap']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $sertifikat_data = json_decode($peserta['sertifikat_data'], true);

                                        if (is_array($sertifikat_data) && !empty($sertifikat_data)) {
                                            foreach ($sertifikat_data as $sertifikat) {
                                                $status = htmlspecialchars($sertifikat['status']);
                                                $masa_berlaku = htmlspecialchars($sertifikat['masa_berlaku']);
                                                
                                                // Remaining days calculation
                                                $today = new DateTime();
                                                $expiry_date = new DateTime($masa_berlaku);
                                                $interval = $today->diff($expiry_date);
                                                $sisa_hari = $interval->format('%r%a');

                                                echo '<p><strong>' . htmlspecialchars($sertifikat['nama']) . '</strong> - Status: ' . $status;

                                                if ($status === 'nonaktif') {
                                                    echo ' (Berlaku hingga: ' . $masa_berlaku . ')';
                                                    
                                                    if ($sisa_hari < 0) {
                                                        echo ' - <span style="color: red;">Kedaluwarsa ' . abs($sisa_hari) . ' hari yang lalu</span>';
                                                    } else {
                                                        echo ' - <span style="color: orange;">Sisa ' . $sisa_hari . ' hari</span>';
                                                    }
                                                }
                                                echo '</p>';
                                                
                                                echo '<a href="uploads/' . htmlspecialchars($sertifikat['file']) . '" target="_blank">Lihat Sertifikat</a><hr>';
                                            }
                                        } else {
                                            echo '<p>Tidak ada sertifikat.</p>';
                                        }
                                        ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>