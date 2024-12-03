<?php
session_start();
include 'koneksi.php'; // Menggunakan koneksi database

// Cek apakah peserta_id diberikan melalui URL
if (isset($_GET['peserta_id'])) {
    $peserta_id = $_GET['peserta_id'];

    // Menghapus data hasil ujian peserta
    $query_reset = "DELETE FROM hasil_ujian WHERE peserta_id = ?";
    $stmt = $conn->prepare($query_reset);
    $stmt->bind_param("i", $peserta_id);
    if ($stmt->execute()) {
        // Redirect kembali ke dashboard HR dengan pesan sukses
        $_SESSION['message'] = "Ujian peserta berhasil di-reset.";
        header("Location: dashboard_hr.php");
    } else {
        // Redirect dengan pesan error jika gagal
        $_SESSION['message'] = "Gagal mereset ujian peserta.";
        header("Location: dashboard_hr.php");
    }
} else {
    // Jika tidak ada peserta_id, redirect kembali ke dashboard HR
    header("Location: dashboard_hr.php");
}
?>
