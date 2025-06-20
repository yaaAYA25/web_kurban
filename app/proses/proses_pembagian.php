<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis = isset($_POST['jenis']) ? strtolower(trim($_POST['jenis'])) : '';

    // Validasi jenis hewan
    if ($jenis !== 'sapi' && $jenis !== 'kambing') {
        header('Location: ../halaman_utama.php?error=jenis_invalid');
        exit;
    }

    // Tentukan hewan_id berdasarkan jenis
    // Asumsikan: 1 = sapi, 2 = kambing
    $hewan_id = ($jenis === 'sapi') ? 1 : 2;

    // Ambil total berat dari hewan_qurban berdasarkan jenis
    $stmt = $koneksi->prepare("SELECT SUM(total_berat) AS total_berat FROM hewan_qurban WHERE jenis = ?");
    $stmt->bind_param("s", $jenis);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $total_berat = $row['total_berat'] ?? 0;

    if ($total_berat <= 0) {
        header('Location: ../halaman_utama.php?error=berat_kosong');
        exit;
    }

    // Ambil semua warga yang kategori pembagian = 'warga' (atau sesuai kebutuhan)
    $warga_result = $koneksi->query("SELECT id_warga FROM warga"); // Contoh filter warga aktif

    if ($warga_result->num_rows == 0) {
        header('Location: ../halaman_utama.php?error=warga_kosong');
        exit;
    }

    $jumlah_warga = $warga_result->num_rows;

    // Hitung pembagian berat per warga
    $berat_per_warga = $total_berat / $jumlah_warga;

    $tanggal = date('Y-m-d');
    $created_at = date('Y-m-d H:i:s');

    // Mulai simpan pembagian daging per warga
    $insert = $koneksi->prepare("INSERT INTO pembagian_daging (warga_id, hewan_id, kategori, jumlah_kg, tanggal, qr_code, created_at) VALUES (?, ?, ?, ?, ?, NULL, ?)");

    if (!$insert) {
        die("Prepare failed: " . $koneksi->error);
    }

    $kategori = 'warga'; // contoh kategori

    // Loop semua warga dan insert
    while ($warga = $warga_result->fetch_assoc()) {
        $warga_id = $warga['id_warga'];

        $insert->bind_param("iisdss", $warga_id, $hewan_id, $kategori, $berat_per_warga, $tanggal, $created_at);
        $insert->execute();
    }

    $insert->close();

    header('Location: ../view/dashboard.php');
    exit;

} else {
    header('Location: ../view/dashboard.php');
    exit;
}
