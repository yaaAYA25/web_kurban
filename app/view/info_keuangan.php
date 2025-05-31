<?php
include "./../koneksi/koneksi.php";
$sql = "SELECT tanggal, jenis, sumber, jumlah, keterangan, total_keuangan FROM keuangan ORDER BY tanggal ASC";
$result = $koneksi->query($sql);
?>

<h2>Informasi Keuangan Kurban</h2>

<table style="border-collapse: collapse; width: 100%; background-color: #fff;">
    <thead>
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Tanggal</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Jenis</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Sumber</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Jumlah</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Keterangan</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Keuangan</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($row['tanggal']) ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; color: <?= $row['jenis'] == 'masuk' ? 'green' : 'red' ?>;">
                    <?= ucfirst($row['jenis']) ?>
                </td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($row['sumber']) ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;">Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($row['keterangan']) ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><strong>Rp <?= number_format($row['total_keuangan'], 2, ',', '.') ?></strong></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>