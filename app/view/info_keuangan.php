<?php
session_start();
include "./../koneksi/koneksi.php";

// Ambil semua data keuangan dari database
$sql = "SELECT tanggal, jenis, sumber, jumlah, keterangan, total_keuangan FROM keuangan ORDER BY tanggal ASC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Info Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background-color: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .masuk { color: green; }
        .keluar { color: red; }
    </style>
</head>
<body>

<h2>Informasi Keuangan Kurban</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Sumber</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Total Keuangan</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td class="<?= $row['jenis'] == 'masuk' ? 'masuk' : 'keluar' ?>">
                <?= ucfirst($row['jenis']) ?>
            </td>
            <td><?= htmlspecialchars($row['sumber']) ?></td>
            <td>Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td><strong>Rp <?= number_format($row['total_keuangan'], 2, ',', '.') ?></strong></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
