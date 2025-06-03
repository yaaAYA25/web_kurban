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
$data_warga = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['warga_id'];
    $nama = $row['nama'];
    $kategori = strtolower($row['kategori']);
    $jenis = strtolower($row['jenis']);
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
    <title>Rincian Pembagian Daging Kurban</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-700 p-6">

    <h2 class="text-xl font-semibold text-cyan-700 border-l-4 border-cyan-500 pl-3 mb-5">
        📋 Rincian Pembagian Daging Kurban
    </h2>

    <div class="overflow-x-auto bg-white rounded-lg shadow-lg border border-cyan-200">
        <table class="min-w-full table-auto text-sm">
            <thead>
                <tr class="bg-cyan-100 text-cyan-800 uppercase tracking-wide text-center">
                    <th rowspan="2" class="py-3 px-4 border-b border-cyan-300 text-left">Nama</th>
                    <th colspan="2" class="py-3 px-4 border-b border-cyan-300">Warga</th>
                    <th colspan="2" class="py-3 px-4 border-b border-cyan-300">Panitia</th>
                    <th colspan="2" class="py-3 px-4 border-b border-cyan-300">Kurban</th>
                    <th rowspan="2" class="py-3 px-4 border-b border-cyan-300">Total (kg)</th>
                </tr>
                <tr class="bg-cyan-50 text-cyan-700 uppercase tracking-wide text-center">
                    <th class="py-2 px-4 border-b border-cyan-300">Sapi</th>
                    <th class="py-2 px-4 border-b border-cyan-300">Kambing</th>

                    <th class="py-2 px-4 border-b border-cyan-300">Sapi</th>
                    <th class="py-2 px-4 border-b border-cyan-300">Kambing</th>

                    <th class="py-2 px-4 border-b border-cyan-300">Sapi</th>
                    <th class="py-2 px-4 border-b border-cyan-300">Kambing</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_warga as $id => $data):
                    $total = $data['warga_sapi'] + $data['warga_kambing']
                        + $data['panitia_sapi'] + $data['panitia_kambing']
                        + $data['kurban_sapi'] + $data['kurban_kambing'];
                ?>
                <tr class="border-b border-cyan-100 hover:bg-cyan-50 transition-colors text-center">
                    <td class="py-2 px-4 font-medium text-left whitespace-nowrap"><?= htmlspecialchars($data['nama']) ?></td>
                    <td class="py-2 px-4"><?= number_format($data['warga_sapi'], 2, ',', '.') ?> kg</td>
                    <td class="py-2 px-4"><?= number_format($data['warga_kambing'], 2, ',', '.') ?> kg</td>
                    <td class="py-2 px-4"><?= number_format($data['panitia_sapi'], 2, ',', '.') ?> kg</td>
                    <td class="py-2 px-4"><?= number_format($data['panitia_kambing'], 2, ',', '.') ?> kg</td>
                    <td class="py-2 px-4"><?= number_format($data['kurban_sapi'], 2, ',', '.') ?> kg</td>
                    <td class="py-2 px-4"><?= number_format($data['kurban_kambing'], 2, ',', '.') ?> kg</td>
                    <td class="py-2 px-4 font-semibold text-cyan-700"><?= number_format($total, 2, ',', '.') ?> kg</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php $koneksi->close(); ?>
</body>
</html>
