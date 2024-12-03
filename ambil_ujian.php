<?php
include 'koneksi.php'; // Menggunakan koneksi database
session_start();

// Cek apakah peserta sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'peserta') {
    header("Location: login.php");
    exit();
}

// Ambil ujian_id dari parameter GET
if (isset($_GET['ujian_id'])) {
    $ujian_id = $_GET['ujian_id'];
} else {
    echo "Ujian ID tidak ditemukan!";
    exit();
}

// Ambil soal ujian
$query_soal = "SELECT * FROM soal WHERE ujian_id = $ujian_id";
$result_soal = mysqli_query($conn, $query_soal);

// Proses penyimpanan jawaban peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jawaban = $_POST['jawaban'];
    $peserta_id = $_SESSION['user_id'];

    // Loop melalui setiap soal dan simpan jawaban
    foreach ($jawaban as $soal_id => $jawaban_peserta) {
        // Simpan jawaban ke dalam tabel jawaban_peserta
        $query_insert = "INSERT INTO jawaban_peserta (peserta_id, soal_id, jawaban) VALUES ('$peserta_id', '$soal_id', '$jawaban_peserta')";
        mysqli_query($conn, $query_insert);

        // Hitung skor
        $query_soal = "SELECT jawaban_benar FROM soal WHERE soal_id = $soal_id";
        $result_soal = mysqli_query($conn, $query_soal);
        $soal_data = mysqli_fetch_assoc($result_soal);
        
        $skor = ($jawaban_peserta === $soal_data['jawaban_benar']) ? 5 : 0; // Setiap jawaban benar mendapatkan 5 poin

        // Simpan skor ke dalam tabel hasil_ujian
        $query_hasil = "INSERT INTO hasil_ujian (peserta_id, ujian_id, skor) VALUES ('$peserta_id', '$ujian_id', '$skor')
                        ON DUPLICATE KEY UPDATE skor = skor + $skor";
        mysqli_query($conn, $query_hasil);
    }

    // Redirect setelah menyimpan jawaban
    header("Location: dashboard_peserta.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center text-primary">Ujian Online</h2>
                <div class="text-center">
                    <h3 id="timer" class="text-danger">10:00</h3>
                </div>
                <form method="POST" action="" id="examForm">
                    <?php
                    if (mysqli_num_rows($result_soal) > 0) {
                        $nomor = 1;
                        while ($soal = mysqli_fetch_assoc($result_soal)) {
                            echo "<div class='card mb-4'>";
                            echo "<div class='card-header bg-primary text-white'>Pertanyaan $nomor</div>";
                            echo "<div class='card-body'>";
                            echo "<p><strong>{$soal['pertanyaan']}</strong></p>";
                            echo "<div class='form-check'>";
                            echo "<input class='form-check-input' type='radio' name='jawaban[{$soal['soal_id']}]' value='A' id='jawabanA{$soal['soal_id']}'>";
                            echo "<label class='form-check-label' for='jawabanA{$soal['soal_id']}'>{$soal['pilihan_a']}</label>";
                            echo "</div>";
                            echo "<div class='form-check'>";
                            echo "<input class='form-check-input' type='radio' name='jawaban[{$soal['soal_id']}]' value='B' id='jawabanB{$soal['soal_id']}'>";
                            echo "<label class='form-check-label' for='jawabanB{$soal['soal_id']}'>{$soal['pilihan_b']}</label>";
                            echo "</div>";
                            echo "<div class='form-check'>";
                            echo "<input class='form-check-input' type='radio' name='jawaban[{$soal['soal_id']}]' value='C' id='jawabanC{$soal['soal_id']}'>";
                            echo "<label class='form-check-label' for='jawabanC{$soal['soal_id']}'>{$soal['pilihan_c']}</label>";
                            echo "</div>";
                            echo "<div class='form-check'>";
                            echo "<input class='form-check-input' type='radio' name='jawaban[{$soal['soal_id']}]' value='D' id='jawabanD{$soal['soal_id']}'>";
                            echo "<label class='form-check-label' for='jawabanD{$soal['soal_id']}'>{$soal['pilihan_d']}</label>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $nomor++;
                        }
                    } else {
                        echo "<div class='alert alert-warning'>Maaf, tidak ada soal yang tersedia untuk ujian ini.</div>";
                    }
                    ?>
                    <input type="hidden" name="ujian_id" value="<?php echo $ujian_id; ?>">
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Kirim Jawaban</button>
                        <a href="dashboard_peserta.php" class="btn btn-secondary btn-lg">Kembali ke Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Timer Script -->
    <script>
        let timer = 600; // 10 minutes in seconds
        const timerElement = document.getElementById("timer");

        function startTimer() {
            const countdown = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timer <= 0) {
                    clearInterval(countdown);
                    document.getElementById("examForm").submit(); // Automatically submit the form
                }
                timer--;
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</body>
</html>
