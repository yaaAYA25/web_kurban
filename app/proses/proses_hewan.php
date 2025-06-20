<?php
include '../koneksi/koneksi.php'; // sesuaikan path file koneksi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $total_berat = $_POST['total_berat'];
    $biaya_total = $_POST['biaya_total'];
    $query = "INSERT INTO hewan_qurban (jenis, jumlah, total_berat, biaya_total)
              VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("siid", $jenis, $jumlah, $total_berat, $biaya_total);

    if ($stmt->execute()) {
        $tanggal = date('Y-m-d');
        $sumber = 'Pembelian hewan qurban';
        $keterangan = "Pembelian $jumlah ekor $jenis";
        $jenis_keuangan = 'keluar';
        $query_keuangan = "INSERT INTO keuangan (tanggal, jenis, sumber, jumlah, keterangan)
                           VALUES (?, ?, ?, ?, ?)";
        $stmt_keuangan = $koneksi->prepare($query_keuangan);
        $stmt_keuangan->bind_param("sssds", $tanggal, $jenis_keuangan, $sumber, $biaya_total, $keterangan);

        if ($stmt_keuangan->execute()) {
            echo "✅ Data hewan dan keuangan berhasil disimpan.<br>";
        } else {
            echo "⚠️ Data hewan tersimpan, tapi gagal menyimpan ke keuangan: " . $stmt_keuangan->error . "<br>";
        }

        echo "<a href='../view/hewan_qurban.php'>Kembali ke Form</a>";
    } else {
        echo "❌ Gagal menyimpan data hewan: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
} else {
    echo "Akses tidak sah.";
}
