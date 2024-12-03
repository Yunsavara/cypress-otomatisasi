<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

$error_message = ""; // Initialize an empty error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencari peserta berdasarkan username
    $query_peserta = "SELECT * FROM peserta WHERE username='$username'";
    $result_peserta = mysqli_query($conn, $query_peserta);
    $peserta = mysqli_fetch_assoc($result_peserta);

    // Mencari HR berdasarkan username
    $query_hr = "SELECT * FROM hr WHERE username='$username'";
    $result_hr = mysqli_query($conn, $query_hr);
    $hr = mysqli_fetch_assoc($result_hr);

    if ($peserta) {
        // Jika peserta ada, periksa password
        if (password_verify($password, $peserta['password'])) {
            // Login sukses untuk peserta
            session_start();
            $_SESSION['user_type'] = 'peserta';
            $_SESSION['user_id'] = $peserta['peserta_id'];
            header("Location: dashboard_peserta.php"); // Ganti dengan halaman peserta
            exit();
        } else {
            $error_message = "Password peserta salah.";
        } 
    } elseif ($hr) {
        // Jika HR ada, periksa password
        if (password_verify($password, $hr['password'])) {
            // Login sukses untuk HR
            session_start();
            $_SESSION['user_type'] = 'hr';
            $_SESSION['user_id'] = $hr['hr_id'];
            header("Location: dashboard_hr.php"); // Ganti dengan halaman HR
            exit();
        } else {
            $error_message = "Password HR salah.";
        }
    } else {
        $error_message = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('backgroundmedsa.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            padding: 20px;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <h2 class="text-center">Login</h2>
                    
                    <!-- Error message display -->
                    <?php if ($error_message): ?>
                        <p class="error-message text-center"><?php echo $error_message; ?></p>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Login</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
