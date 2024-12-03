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
    
    <!-- Add the recruitment process flow image above career opportunities -->
    <div class="container text-center my-4">
        <img src="alur_logo.jpg" alt="Alur Rekrutmen" class="img-fluid">
    </div>

    <div class="container my-5 text-center">
        <!-- Peluang Karir Button -->
        <a href="karir.php">
            <button class="btn" style="background-color: #f5deb3; color: black; padding: 10px 20px; font-size: 18px; font-weight: bold; border-radius: 5px; border: none;">
                Peluang Karir
            </button>
        </a>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
