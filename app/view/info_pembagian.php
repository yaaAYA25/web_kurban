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
    <meta charset="UTF-8">
    <title>Rincian Pembagian Daging Kurban</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        /* body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 2rem;
            background-color: #f4f6f9;
            color: #2c3e50;
        } */

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #1a202c;
            border-left: 4px solid #1a9cb8;
            padding-left: 12px;
        }

        .table-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            overflow-x: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 960px;
        }

        thead {
            background-color: #e8f5e9;
        }

        th {
            text-align: left;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #1a9cb8;
            border-bottom: 1px solid #c8e6c9;
        }

        td {
            padding: 12px 16px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        td.total {
            font-weight: 600;
            color: #1a9cb8;
        }

        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 1rem;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                padding: 1rem;
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #555;
            }
        }
    </style>
</head>
<body>

    <h2 class="section-title">📋 Rincian Pembagian Daging Kurban</h2>

    <div class="table-wrapper">
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
                        <td data-label="Nama"><?= htmlspecialchars($data['nama']) ?></td>
                        <td data-label="Sapi (Warga)"><?= number_format($data['warga_sapi'], 2, ',', '.') ?> kg</td>
                        <td data-label="Kambing (Warga)"><?= number_format($data['warga_kambing'], 2, ',', '.') ?> kg</td>
                        <td data-label="Sapi (Panitia)"><?= number_format($data['panitia_sapi'], 2, ',', '.') ?> kg</td>
                        <td data-label="Kambing (Panitia)"><?= number_format($data['panitia_kambing'], 2, ',', '.') ?> kg</td>
                        <td data-label="Sapi (Kurban)"><?= number_format($data['kurban_sapi'], 2, ',', '.') ?> kg</td>
                        <td data-label="Kambing (Kurban)"><?= number_format($data['kurban_kambing'], 2, ',', '.') ?> kg</td>
                        <td data-label="Total (kg)" class="total"><?= number_format($total, 2, ',', '.') ?> kg</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php $koneksi->close(); ?>
</body>
</html>
