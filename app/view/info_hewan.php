<?php
include '../koneksi/koneksi.php'; // pastikan file koneksi ini ada dan benar

// Ambil data hewan qurban
$query = "SELECT * FROM hewan_qurban ORDER BY created_at DESC";
$result = $koneksi->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Info Hewan Qurban</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
        }
        th, td {
            padding: 10px 15px;
            border: 1px solid #999;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Informasi Hewan Qurban</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Total Berat (kg)</th>
            <th>Biaya Total (Rp)</th>
            <th>Tanggal Input</th>
        </tr>
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . ucfirst($row['jenis']) . "</td>";
            echo "<td>" . $row['jumlah'] . "</td>";
            echo "<td>" . $row['total_berat'] . "</td>";
            echo "<td>Rp " . number_format($row['biaya_total'], 2, ',', '.') . "</td>";
            echo "<td>" . date('d-m-Y H:i', strtotime($row['created_at'])) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
