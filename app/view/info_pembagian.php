<?php
session_start();
include "./../koneksi/koneksi.php";

$sql = "
SELECT 
    pd.warga_id,
    w.nama,
    pd.kategori,
    h.jenis,
    SUM(pd.jumlah_kg) AS total_kg
FROM pembagian_daging pd
JOIN warga w ON pd.warga_id = w.id_warga
LEFT JOIN hewan_qurban h ON pd.hewan_id = h.id
GROUP BY pd.warga_id, w.nama, pd.kategori, h.jenis
ORDER BY w.nama
";

$result = $koneksi->query($sql);

// Susun data per warga
$data_warga = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['warga_id'];
    $nama = $row['nama'];
    $kategori = strtolower($row['kategori']); // warga, panitia, kurban
    $jenis = strtolower($row['jenis']);       // sapi, kambing
    $berat = floatval($row['total_kg']);

    if (!isset($data_warga[$id])) {
        $data_warga[$id] = [
            'nama' => $nama,
            'warga_sapi' => 0,
            'warga_kambing' => 0,
            'panitia_sapi' => 0,
            'panitia_kambing' => 0,
            'kurban_sapi' => 0,
            'kurban_kambing' => 0,
        ];
    }

    $key = "{$kategori}_{$jenis}";
    if (isset($data_warga[$id][$key])) {
        $data_warga[$id][$key] += $berat;
    }
}
?>

<h2 style="font-family: Arial, sans-serif; margin-bottom: 20px;">Rincian Pembagian Daging Kurban</h2>

<table style="font-family: Arial, sans-serif; border-collapse: collapse; width: 100%; background-color: #fff;">
    <thead>
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Nama</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Sapi (Warga)</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Kambing (Warga)</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Sapi (Panitia)</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Kambing (Panitia)</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Sapi (Kurban)</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Kambing (Kurban)</th>
            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;">Total (kg)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_warga as $id => $data):
            $total = $data['warga_sapi'] + $data['warga_kambing']
                + $data['panitia_sapi'] + $data['panitia_kambing']
                + $data['kurban_sapi'] + $data['kurban_kambing'];
        ?>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($data['nama']) ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= number_format($data['warga_sapi'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= number_format($data['warga_kambing'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= number_format($data['panitia_sapi'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= number_format($data['panitia_kambing'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= number_format($data['kurban_sapi'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= number_format($data['kurban_kambing'], 2, ',', '.') ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;"><?= number_format($total, 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $koneksi->close(); ?>