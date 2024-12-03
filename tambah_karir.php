<?php
// koneksi ke database
include 'koneksi.php';

// Proses penambahan karir baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posisi = $_POST['posisi'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($gambar);

    // Pindahkan file gambar ke folder uploads
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        // Simpan data karir ke database
        $query_insert = "INSERT INTO karir (posisi, deskripsi, gambar) VALUES ('$posisi', '$deskripsi', '$gambar')";
        mysqli_query($conn, $query_insert);

        // Redirect ke halaman utama setelah menambah karir
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lowongan Karir</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head> 
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Tambah Lowongan Karir</h2>

        <!-- Form untuk menambah karir baru -->
        <form action="tambah_karir.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="posisi">Posisi</label>
                <input type="text" class="form-control" id="posisi" name="posisi" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input type="file" class="form-control-file" id="gambar" name="gambar" required>
            </div>
            <button type="submit" class="btn btn-success">Tambah Karir</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
