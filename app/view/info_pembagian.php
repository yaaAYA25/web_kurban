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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Rincian Pembagian Daging Lengkap</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background-color: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 14px; }
        th { background-color: #f2f2f2; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>

<h2>Rincian Pembagian Daging Kurban</h2>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Sapi (Warga)</th>
            <th>Kambing (Warga)</th>
            <th>Sapi (Panitia)</th>
            <th>Kambing (Panitia)</th>
            <th>Sapi (Kurban)</th>
            <th>Kambing (Kurban)</th>
            <th>Total (kg)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_warga as $id => $data): 
            $total = $data['warga_sapi'] + $data['warga_kambing']
                   + $data['panitia_sapi'] + $data['panitia_kambing']
                   + $data['kurban_sapi'] + $data['kurban_kambing'];
        ?>
        <tr>
            <td><?= htmlspecialchars($data['nama']) ?></td>
            <td><?= number_format($data['warga_sapi'], 2, ',', '.') ?></td>
            <td><?= number_format($data['warga_kambing'], 2, ',', '.') ?></td>
            <td><?= number_format($data['panitia_sapi'], 2, ',', '.') ?></td>
            <td><?= number_format($data['panitia_kambing'], 2, ',', '.') ?></td>
            <td><?= number_format($data['kurban_sapi'], 2, ',', '.') ?></td>
            <td><?= number_format($data['kurban_kambing'], 2, ',', '.') ?></td>
            <td><strong><?= number_format($total, 2, ',', '.') ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

<?php $koneksi->close(); ?>
