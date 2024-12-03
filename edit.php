<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah HR sudah login
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'hr') {
    header("Location: login.php");
    exit();
}

// Cek jika ID peserta diberikan
if (isset($_GET['id'])) {
    $peserta_id = $_GET['id'];

    // Ambil data peserta dari database dengan prepared statement
    $stmt = $conn->prepare("SELECT * FROM peserta WHERE peserta_id = ?");
    $stmt->bind_param("i", $peserta_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Jika data peserta tidak ditemukan
    if (!$row) {
        echo "Data peserta tidak ditemukan.";
        exit();
    }
} else {
    echo "ID peserta tidak diberikan.";
    exit();
}

// Proses update data peserta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $lulusan = $_POST['lulusan'];
    $pekerjaan_yang_dilamar = $_POST['pekerjaan_yang_dilamar'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Update data peserta dengan prepared statement
    if (empty($password)) {
        $query_update = "UPDATE peserta SET 
            nama_lengkap = ?, 
            email = ?, 
            alamat = ?, 
            tanggal_lahir = ?, 
            lulusan = ?, 
            pekerjaan_yang_dilamar = ?, 
            username = ? 
            WHERE peserta_id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("sssssssi", $nama_lengkap, $email, $alamat, $tanggal_lahir, $lulusan, $pekerjaan_yang_dilamar, $username, $peserta_id);
    } else {
        // Hash password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query_update = "UPDATE peserta SET 
            nama_lengkap = ?, 
            email = ?, 
            alamat = ?, 
            tanggal_lahir = ?, 
            lulusan = ?, 
            pekerjaan_yang_dilamar = ?, 
            username = ?, 
            password = ? 
            WHERE peserta_id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("ssssssssi", $nama_lengkap, $email, $alamat, $tanggal_lahir, $lulusan, $pekerjaan_yang_dilamar, $username, $hashed_password, $peserta_id);
    }

    // Eksekusi dan cek hasil
    if ($stmt_update->execute()) {
        // Mengirim email ke peserta
        $subject = "Perbaruan Informasi Akun Anda";
        $message = "Halo " . $nama_lengkap . ",\n\nBerikut adalah informasi terbaru akun Anda:\n";
        $message .= "Username: " . $username . "\n";
        
        if (!empty($password)) {
            $message .= "Password: " . $password . "\n"; // Password yang baru diinput
        } else {
            $message .= "Password Anda tetap sama.\n";
        }

        $message .= "\nBerikut link pengejaan soaln dengan username dan password id atas https://karir.medikaprakarsa.co.id/login.php";
        $headers = "From: it@adhityaputrapratama.my.id";

        // Kirim email
        if (mail($email, $subject, $message, $headers)) {
            echo "Email pemberitahuan berhasil dikirim.";
        } else {
            echo "Gagal mengirim email pemberitahuan.";
        }

        // Arahkan kembali ke dashboard HR
        header("Location: dashboard_hr.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta</title>
    <!-- Menambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Peserta</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($row['nama_lengkap']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($row['tanggal_lahir']); ?>" required>
            </div>

            <div class="form-group">
                <label for="lulusan">Lulusan:</label>
                <input type="text" class="form-control" id="lulusan" name="lulusan" value="<?php echo htmlspecialchars($row['lulusan']); ?>" required>
            </div>

            <div class="form-group">
                <label for="pekerjaan_yang_dilamar">Pekerjaan yang Dilamar:</label>
                <select class="form-control" id="pekerjaan_yang_dilamar" name="pekerjaan_yang_dilamar" required>
                    <option value="Programmer" <?php if ($row['pekerjaan_yang_dilamar'] == "Programmer") echo 'selected'; ?>>Programmer</option>
                    <option value="Designer" <?php if ($row['pekerjaan_yang_dilamar'] == "Designer") echo 'selected'; ?>>Designer</option>
                    <option value="Marketing" <?php if ($row['pekerjaan_yang_dilamar'] == "Marketing") echo 'selected'; ?>>Marketing</option>
                </select>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password (biarkan kosong jika tidak ingin mengubah):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="dashboard_hr.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </form>
    </div>

    <!-- Menambahkan Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
