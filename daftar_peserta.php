<?php
// Tampilkan semua error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; // File ini harus berisi koneksi ke database

// Tangkap parameter `karir_id` dari URL
$karir_id = isset($_GET['karir_id']) ? $_GET['karir_id'] : null;
$posisi = '';

// Ambil nama posisi berdasarkan `karir_id` jika ada
if ($karir_id) {
    $stmt = $conn->prepare("SELECT posisi FROM karir WHERE karir_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $karir_id);
        $stmt->execute();
        $stmt->bind_result($posisi);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Kesalahan dalam query posisi: " . $conn->error;
        exit;
    }
}

$peserta_terdaftar = false; // Flag untuk mengecek apakah pendaftaran berhasil

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $lulusan = $_POST['lulusan'];
    $tujuan_melamar = $_POST['tujuan_melamar'];
    $bidang_disenangi = $_POST['bidang_disenangi'];
    $bidang_tidak_disenangi = $_POST['bidang_tidak_disenangi'];
    $pengetahuan_keahlian = $_POST['pengetahuan_keahlian'];
    $gaji_harapan = $_POST['gaji_harapan'];
    $kapan_bisa_bekerja = $_POST['kapan_bisa_bekerja'];
    $pekerjaan_yang_dilamar = $posisi; // Ambil posisi yang didapat dari query

    // Mengupload CV
    if (isset($_FILES['cv'])) {
        $cv_file = $_FILES['cv'];
        $cv_file_name = time() . '_' . basename($cv_file['name']);
        $cv_file_path = 'uploads/' . $cv_file_name;

        if ($cv_file['error'] === UPLOAD_ERR_OK) {
            if (!move_uploaded_file($cv_file['tmp_name'], $cv_file_path)) {
                echo "Gagal mengupload CV.";
                exit;
            }
        } else {
            echo "Gagal mengupload CV. Kesalahan: " . $cv_file['error'];
            exit;
        }
    }

    // Mengolah sertifikat
    $sertifikat_data = [];
    if (isset($_POST['sertifikat_status'])) {
        foreach ($_POST['sertifikat_status'] as $index => $sertifikat_status) {
            $nama_sertifikat = $_POST['nama_sertifikat'][$index];
            $masa_berlaku = $_POST['masa_berlaku'][$index] ?? null;
            
            $file_sertifikat = $_FILES['file_sertifikat']['name'][$index];
            $file_sertifikat_temp = $_FILES['file_sertifikat']['tmp_name'][$index];
            $file_sertifikat_name = time() . '_' . basename($file_sertifikat);
            $file_sertifikat_path = 'uploads/' . $file_sertifikat_name;

            if ($file_sertifikat_temp && !move_uploaded_file($file_sertifikat_temp, $file_sertifikat_path)) {
                echo "Gagal mengupload sertifikat: " . $file_sertifikat_name;
                exit;
            }

            $sertifikat_data[] = [
                'nama' => $nama_sertifikat,
                'status' => $sertifikat_status,
                'masa_berlaku' => $masa_berlaku,
                'file' => $file_sertifikat_name,
            ];
        }
    }

    // Convert sertifikat data to JSON
    $sertifikat_json = json_encode($sertifikat_data);

    // Simpan data peserta ke tabel `peserta`
    $stmt = $conn->prepare("INSERT INTO peserta (nama_lengkap, email, alamat, tanggal_lahir, lulusan, pekerjaan_yang_dilamar, cv_file, sertifikat_data, tujuan_melamar, bidang_disenangi, bidang_tidak_disenangi, pengetahuan_keahlian, gaji_harapan, kapan_bisa_bekerja) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $nama_lengkap, $email, $alamat, $tanggal_lahir, $lulusan, $pekerjaan_yang_dilamar, $cv_file_name, $sertifikat_json, $tujuan_melamar, $bidang_disenangi, $bidang_tidak_disenangi, $pengetahuan_keahlian, $gaji_harapan, $kapan_bisa_bekerja);

    if ($stmt->execute()) {
        $peserta_id = $stmt->insert_id;

        // Simpan sertifikat di tabel `sertifikat`
        foreach ($sertifikat_data as $sertifikat) {
            $nama_sertifikat = $sertifikat['nama'];
            $status = $sertifikat['status'];
            $masa_berlaku = $sertifikat['masa_berlaku'];
            $file_sertifikat = $sertifikat['file'];

            $sertifikat_stmt = $conn->prepare("INSERT INTO sertifikat (peserta_id, nama_sertifikat, status, masa_berlaku, file_sertifikat) VALUES (?, ?, ?, ?, ?)");
            $sertifikat_stmt->bind_param("issss", $peserta_id, $nama_sertifikat, $status, $masa_berlaku, $file_sertifikat);
            $sertifikat_stmt->execute();
            $sertifikat_stmt->close();
        }

        // Simpan riwayat pekerjaan di tabel `riwayat_pekerjaan`
        foreach ($_POST['nama_perusahaan'] as $index => $nama_perusahaan) {
            $posisi_pekerjaan = $_POST['posisi_pekerjaan'][$index];
            $tanggal_mulai = $_POST['tanggal_mulai'][$index];
            $tanggal_selesai = $_POST['tanggal_selesai'][$index];

            $riwayat_stmt = $conn->prepare("INSERT INTO riwayat_pekerjaan (peserta_id, nama_perusahaan, posisi, tanggal_mulai, tanggal_selesai) VALUES (?, ?, ?, ?, ?)");
            $riwayat_stmt->bind_param("issss", $peserta_id, $nama_perusahaan, $posisi_pekerjaan, $tanggal_mulai, $tanggal_selesai,);
            $riwayat_stmt->execute();
            $riwayat_stmt->close();
        }

// Kirim email
        $to = "adhityapp1107@gmail.com, adhityaputrap1107@gmail.com";
        $subject = "Pendaftaran Peserta Baru";
        $message = "Nama Lengkap: $nama_lengkap\n".
                   "Email: $email\n".
                   "Alamat: $alamat\n".
                   "Tanggal Lahir: $tanggal_lahir\n".
                   "Lulusan: $lulusan\n".
                   "Pekerjaan yang Dilamar: $pekerjaan_yang_dilamar\n".
                   "CV: $cv_file_name\n";

        $headers = "From: it@medikaprakarsa.co.id\r\n" .
                   "Reply-To: it@medikaprakarsa.co.id\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $peserta_terdaftar = true; // Set flag pendaftaran berhasil
        } else {
            echo "Pendaftaran berhasil, tetapi gagal mengirim email konfirmasi.";
        }
    } else {
        echo "Gagal mendaftar: " . $stmt->error;
    }

    // Menutup statement
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Peserta</title>
    <!-- Menambahkan Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_index(1).php'; ?>
    <div class="container">
        <h2 class="my-4">Pendaftaran Peserta</h2>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div> 

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>

            <div class="form-group">
                <label for="lulusan">Lulusan:</label>
                <input type="text" class="form-control" id="lulusan" name="lulusan" required>
            </div>

            <div class="form-group">
                    <label for="pekerjaan_yang_dilamar">Pekerjaan yang Dilamar:</label>
                    <input type="text" class="form-control" id="pekerjaan_yang_dilamar" name="pekerjaan_yang_dilamar" value="<?php echo htmlspecialchars($posisi); ?>">
             </div>
            
            <!-- Kolom riwayat pekerjaan dinamis -->
            <div id="riwayat_pekerjaan_container">
                <div class="riwayat_pekerjaan_item">
                    <h4>Riwayat Pekerjaan</h4>
                    <div class="form-group">
                        <label for="nama_perusahaan">Nama Perusahaan:</label>
                        <input type="text" class="form-control" name="nama_perusahaan[]" required>
                    </div>
                    <div class="form-group">
                        <label for="posisi">Posisi:</label>
                        <input type="text" class="form-control" name="posisi_pekerjaan[]" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai:</label>
                        <input type="date" class="form-control" name="tanggal_mulai[]" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai">Tanggal Selesai:</label>
                        <input type="date" class="form-control" name="tanggal_selesai[]">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="tambah_riwayat_pekerjaan">Tambah Riwayat Pekerjaan</button>

            <!-- Kolom sertifikat dinamis -->
            <div id="sertifikat_container">
                <div class="sertifikat_item">
                    <div class="form-group">
                        <label for="nama_sertifikat">Nama Sertifikat:</label>
                        <input type="text" class="form-control" name="nama_sertifikat[]" required>
                    </div>

                    <div class="form-group">
                        <label for="sertifikat_status">Status Sertifikat:</label>
                        <select class="form-control" name="sertifikat_status[]" onchange="toggleMasaBerlaku(this)">
                             <option value="">Pilih Sertifikat</option>
                            <option value="aktif">Sertifikat Aktif</option>
                            <option value="nonaktif">Sertifikat Non Aktif</option>
                        </select>
                    </div>

                    <div class="form-group masa_berlaku" style="display:none;">
                        <label for="masa_berlaku">Masa Berlaku:</label>
                        <input type="date" class="form-control" name="masa_berlaku[]" placeholder="Masa Berlaku Sertifikat">
                    </div>

                    <div class="form-group">
                        <label for="file_sertifikat">File Sertifikat:</label>
                        <input type="file" class="form-control" name="file_sertifikat[]" required>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" onclick="addSertifikat()" id="tambah_sertifikat">Tambah Sertifikat</button>

            <div class="form-group">
                <label for="cv">Upload CV:</label>
                <input type="file" class="form-control" id="cv" name="cv" required>
            </div>
            <div class="form-group">
            <label for="tujuan_melamar">Tujuan Anda melamar di perusahaan kami?</label>
            <textarea class="form-control" id="tujuan_melamar" name="tujuan_melamar" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="bidang_disenangi">Bidang pekerjaan yang disenangi?</label>
                <input type="text" class="form-control" id="bidang_disenangi" name="bidang_disenangi" required>
            </div>
            
            <div class="form-group">
                <label for="bidang_tidak_disenangi">Bidang pekerjaan yang tidak Anda senangi?</label>
                <input type="text" class="form-control" id="bidang_tidak_disenangi" name="bidang_tidak_disenangi" required>
            </div>
            
            <div class="form-group">
                <label for="pengetahuan_keahlian">Pengetahuan dan keahlian yang Anda kuasai?</label>
                <textarea class="form-control" id="pengetahuan_keahlian" name="pengetahuan_keahlian" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="gaji_harapan">Berapakah gaji dan tunjangan lain yang Anda harapkan?</label>
                <input type="text" class="form-control" id="gaji_harapan" name="gaji_harapan" required>
            </div>
    
    <div class="form-group">
        <label for="kapan_bisa_bekerja">Kapan anda dapat mulai bekerja ?</label>
        <input type="text" class="form-control" id="kapan_bisa_bekerja" name="kapan_bisa_bekerja" required>
    </div>

            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    </div>

    <script>
        function addSertifikat() {
            const container = document.getElementById('sertifikat_container');
            const sertifikatItem = document.createElement('div');
            sertifikatItem.className = 'sertifikat_item';

            sertifikatItem.innerHTML = `
                <div class="form-group">
                    <label for="nama_sertifikat">Nama Sertifikat:</label>
                    <input type="text" class="form-control" name="nama_sertifikat[]" required>
                </div>
                <div class="form-group">
                    <label for="sertifikat_status">Status Sertifikat:</label>
                    <select class="form-control" name="sertifikat_status[]" onchange="toggleMasaBerlaku(this)">
                         <option value="">Pilih Sertifikat</option>
                        <option value="aktif">Sertifikat Aktif</option>
                        <option value="nonaktif">Sertifikat Non Aktif</option>
                    </select>
                </div>
                <div class="form-group masa_berlaku" style="display:none;">
                    <label for="masa_berlaku">Masa Berlaku:</label>
                    <input type="date" class="form-control" name="masa_berlaku[]" placeholder="Masa Berlaku Sertifikat">
                </div>
                <div class="form-group">
                    <label for="file_sertifikat">File Sertifikat:</label>
                    <input type="file" class="form-control" name="file_sertifikat[]" required>
                </div>
            `;

            container.appendChild(sertifikatItem);
        }

        function toggleMasaBerlaku(selectElement) {
            const masaBerlakuGroup = selectElement.closest('.sertifikat_item').querySelector('.masa_berlaku');
            if (selectElement.value === 'nonaktif') {
                masaBerlakuGroup.style.display = 'block';
            } else {
                masaBerlakuGroup.style.display = 'none';
            }
        }
        
        document.getElementById('tambah_riwayat_pekerjaan').addEventListener('click', function() {
        const riwayatPekerjaanContainer = document.getElementById('riwayat_pekerjaan_container');
        const newRiwayatPekerjaan = document.createElement('div');
        newRiwayatPekerjaan.className = 'riwayat_pekerjaan_item';
        newRiwayatPekerjaan.innerHTML = `
            <div class="form-group">
                <label for="nama_perusahaan">Nama Perusahaan:</label>
                <input type="text" class="form-control" name="nama_perusahaan[]" required>
            </div>
            <div class="form-group">
                <label for="posisi">Posisi:</label>
                <input type="text" class="form-control" name="posisi_pekerjaan[]" required>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai:</label>
                <input type="date" class="form-control" name="tanggal_mulai[]" required>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai:</label>
                <input type="date" class="form-control" name="tanggal_selesai[]">
            </div>
        `;
        riwayatPekerjaanContainer.appendChild(newRiwayatPekerjaan);
        });
    </script>
</body>
</html>
