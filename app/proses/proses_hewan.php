<?php
include '../koneksi/koneksi.php'; // sesuaikan path file koneksi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $total_berat = $_POST['total_berat'];
    $biaya_total = $_POST['biaya_total'];

    // Query simpan
    $query = "INSERT INTO hewan_qurban (jenis, jumlah, total_berat, biaya_total)
              VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("siid", $jenis, $jumlah, $total_berat, $biaya_total);

    if ($stmt->execute()) {
        echo "✅ Data berhasil disimpan.<br>";
        echo "<a href='../view/hewan_qurban.php'>Kembali ke Form</a>";
    } else {
        echo "❌ Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
} else {
    echo "Akses tidak sah.";
}
