<?php
include 'koneksi.php';

// Tentukan jumlah data per halaman
$limit = 6;

// Dapatkan halaman saat ini dari parameter URL, atau set ke 1 jika tidak ada
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil data karir dari database dengan batasan jumlah data per halaman
$query_karir = "SELECT * FROM karir LIMIT $limit OFFSET $offset";
$result_karir = mysqli_query($conn, $query_karir);

// Hitung total halaman
$total_query = "SELECT COUNT(*) as total FROM karir";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karir - Pendaftaran Pegawai</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_index(1).php'; ?>
    <div class="container my-5">
        <h2 class="text-center mb-4">Peluang Karir</h2>

        <div class="row">
            <?php while ($karir = mysqli_fetch_assoc($result_karir)): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="uploads/<?php echo $karir['gambar']; ?>" class="card-img-top" alt="Gambar <?php echo htmlspecialchars($karir['posisi']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($karir['posisi']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($karir['deskripsi']); ?></p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="daftar_peserta.php?karir_id=<?php echo $karir['karir_id']; ?>" class="btn btn-primary">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
